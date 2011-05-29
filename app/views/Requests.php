<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=MacRoman">
    <title></title>
  </head>
  <body>
    <h1>Requests <?php echo $this->from ?> to <?php echo $this->to ?></h1>
    <ul>
    <?php foreach($this->requests as $request): ?>
      <li>Time: <?php echo $request['time'] ?>, length: <?php echo $request['length'] ?></li>
    <?php endforeach ?>
     </ul>
    <ul class="pages">
    <?php for($i=1; $i < $this->pages; $i++): ?>
      <li><a href="/public/requests/page/<?php echo $i ?>"><?php echo $i ?></a></li>
    <?php endfor ?>
    </ul>
    
    <form action="" method="POST">
      <input type="text" name="text" id="text" />
      <input type="submit">
    </form>
    <?php print_r($this->request) ?>
  </body>
</html>