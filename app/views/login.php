<?php include('includes/header.php'); ?>

<section class="text-center">
  <h1><?php echo $title; ?></h1>
  <form method="post">
    <input type="text" name="username" placeholder="Username" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit"><?php echo $title; ?></button>
  </form>
</section>

<?php include('includes/footer.php'); ?>