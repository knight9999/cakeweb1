# cakeweb1

## インストール方法


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



