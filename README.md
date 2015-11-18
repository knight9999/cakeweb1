# cakeweb1

## インストール方法

チェックアウトしたら、Configディレクトリ以下にemail.phpとdatabase.phpを作成してください。(email.php.default,database.php.defaultをそれぞれコピーして編集してください）

その後、Composerを利用して、以下のコマンドで必要なファイル(CakePHPとCakedc/Migrations)をダウンロードしてください。

    $ composer install

tmpディレクトリを作成してください。権限は、Apacheユーザーおよびコマンドラインを実行するユーザーで書き込めるようにしてください。

## 使い方

プロジェクトには、すでに２つのマイグレーションファイルが用意されています。Config/Migration以下の

    1445792805_books.php
    1445873987_insert_records_for_books.php

の２つです。

　まずは、次を実行して、データベースを初期状態にしてください。

    $ Console/cake Migrations.migration run reset

これを実行すると、schema_migrationsテーブルが作成されます。これは、最初の１回のみです。

　次に、最初のマイグレーションファイルを実行します。

    $ Console/cake Migrations.migration run up

1つ目のマイグレーションファイルが適用されて、booksテーブルが作成されます。

　さらに、次のマイグレーションファイルを実行します。

    $ Console/cake Migrations.migration run up

2つ目のマイグレーションファイルが適用されて、booksテーブルにレコードが1つ作成されます。

Webサーバーを立ち上げて、/booksを確認すると、１レコードが確認できます。ただし、ここで後で述べるように、tmpディレクトリの権限問題によるエラーが発生します。詳しくは、セクション3.を確認してください。

　2つ目のマイグレーションファイルをキャンセルする場合は

    $ Console/cake Migrations.migration run down

で出来ます。

　初期状態に戻すのは

    $ Console/cake Migrations.migration run reset

すべてのマイグレーションを適用するのは

    $ Console/cake Migrations.migration run all

で出来ます。

## ディレクトリの権限の問題について

cakedc Migrationsを使うと、コマンドラインからtmpディレクトリ以下にキャッシュファイル等を書き込みます。一方、アプリケーションサーバーからもtmpディレクトリ以下に書き込みが発生するので、コマンドラインの実行ユーザーとApacheの実行ユーザーが異なると、上書きできずにエラーが発生してしまいます。

解決方法としては、おもに以下の３つがあると思います。

1. コマンドライン実行前後で、tmpでディレクトリ以下を毎回削除する。
面倒ですが、コマンドライン実行前にtmp以下を消すことで、コマンドラインを安全に実行し、コマンドライン実行後に再度tmp以下を消すことで、apacheが書き込めるようにします。

    sudo rm -rf tmp/*
    
2. 書き込みエラーが出る度に、毎回、権限設定を繰り返す。
問題になるのは、新規にディレクトリやファイルを生成するときなので、エラーが起きたら権限設定を繰り返せば、いずれはエラーが出なくなります。

    sudo chmod -R a+w tmp/*
    
3. パーミッションを設定して、干渉しないようにする。
幾つか方法がありますが、ここでは以下の3つを設定することで問題を回避します。

*3-1. Apacheユーザーのグループをコマンドラインを実行するユーザーと同じグループにする。*

httpd.confを編集して、グループを設定します。

    Group myaccount

*3-2. Apacheの設定*

umaskを002にしてください。
Macの場合は、/System/Library/LaunchDaemons/org.apache.httpd.plistのdictセクションに

    <key>Umask</key>
    <integer>002</integer>

を追加して、Apacheを再起動してください。
CentOS7であれば、/usr/lib/systemd/system/httpd.service に
    
    [Service]
    UMask=0002

のように記述して、Apacheを再起動してください。
CentOS6であれば、/etc/sysconfig/httpdに

    umask 002

を追加してください。
こうすることにより、tmp以下にapacheがディレクトリを作成した場合、その権限が0775 (02775)になります。

*3-3. tmpディレクトリの権限設定*

以下の手順により、tmpディレクトリを空にした状態で、sgidを設定してください。

    $ sudo rm -rf tmp/*
    $ sudo chmod g+w tmp
    $ sudo chmod g+s tmp

こうすることにより、tmp以下にapacheがディレクトリを作成した場合も、グループユーザーは自分のグループ(=git cloneしたユーザーのグループ)のままになります。

## マイグレーションファイルの作成

### データベースの更新サイクルについて

　CakeDC Migrationsプラグインを利用したデータベースの更新サイクルは、次の二つがあります。

*1 Auto-Migration Files

　データベースは手動で更新し、更新した内容をもとに自動的にマイグレーションファイルを作成する。
　この場合、データベースは手動で更新しているので、自分ではマイグレーションファイルを使いません。作成したマイグレーションファイルは、gitでコミットし、他の開発者が使います。
　扱えるのは、テーブル情報のみ（カラムやインデックスなど）です。レコードは扱えません。

*2 Manually Creating Migration

　空のマイグレーションファイルを作成し、更新したいデータベースの作業を自分で記述する。マイグレーションファイルを適用して、データベースを更新する。
　この場合、空のマイグレーションファイルに、更新時(up)の手続きと、ロールバック時(down)の手続きを記述します。また、beforeとafterメソッドに手続きを記述することで、レコードの追加やデータの変更（新しく出来たカラムに初期値を入れるなど）なども可能です。レコードの追加は、基本的にモデルを通して行います。
　
### Auto-Migration Files

  1. もしまだやっていなければ、最初のマイグレーションを行います。
    
        $ Console/cake Migrations.migration run reset
    
  2. 次に、現在のデータベースの状況に対応したSchemaファイルを作成します。  
    
        $ cake schema generate -f  
    
（スキーマファイルの作り方を尋ねられたら、既存のスキーマファイルを上書きするのであればOverwriteを選択します。Snapshotでも問題ないかは未確認）  
　オプションのｆは、モデルがないテーブルについてもスキーマを作成するかです。  
  3. この状態で、SQLを使って、テーブルの追加、カラムの変更などを行います。
  4. その後、  
    
        $ Console/cake Migrations.migration generate  
    
で、新しいマイグレーションファイルを作成します。これで、マイグレーションファイルが出来ました。この新しいマイグレーションファイルに合わせて、スキーマファイルを更新するか尋ねられるので、更新しない(Quit)か、上書き(Overwrite)してください。  

### Manually Creating Migration


## 参考サイト

ドキュメント
https://github.com/CakeDC/migrations/tree/master/Docs/Documentation

チュートリアル
https://github.com/CakeDC/migrations/blob/master/Docs/Tutorials/Quick-Start.md


