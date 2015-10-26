<h1>Books</h1>

<table>
  <tr>
    <th>Id</th>
    <th>Name</th>
    <th>Body</th>
  </tr>
  <?php foreach ($books as $book): ?>
  <tr>
    <td><?php echo $book['Book']['id']; ?></td>
    <td>
        <?php echo $book['Book']['name']; ?>
    </td>
    <td>
        <?php echo $book['Book']['body']; ?>
    </tr>
  </tr>
  <?php endforeach; ?>
  <?php unset($book); ?>


</table>

