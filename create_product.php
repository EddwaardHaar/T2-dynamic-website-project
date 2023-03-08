<?php
$title = "Adding items";
$stylesheet = "";
$extra = "";
include "partials/header.php";
include "databases/db.php";
?>
<div class="row justify-content-center">
  <!--Product form-->
  <div class="col-4">
    <h2>Add Product</h2>
    <form method="post" action="" onsubmit="return valProd()" name="product">
      <input type="text" name="name" placeholder="Product name" required><br>
      <input type="text" name="desc" placeholder="Description" required><br>
      <input type="number" name="price" placeholder="Price (int)" required><br>
      <!--Select category-->
      <?php
      $query = "SELECT category_id, category_name FROM product_category";
      $result = $conn->query($query);
      if ($result->num_rows > 0) {
        echo "<select name='category'>";
        //Loop through query results
        while ($row = mysqli_fetch_assoc($result)){ //Fethces next row
          echo "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
        }
        echo "</select><br>";
      }
      else
        echo "No catagories found<br>";
      ?>
      <!--Select image-->
      <?php
      $query = "SELECT image_id, title FROM product_image";
      $result = $conn->query($query);
      if ($result->num_rows > 0) {
        echo "<select name='image'>";
        while ($row = mysqli_fetch_assoc($result)){
          echo "<option value='" . $row['image_id'] . "'>" . $row['title'] . "</option>";
        }
        echo "</select><br>";
      }
      else
        echo "No images found<br>";
      ?>
      <input type="submit" value="Submit" name="psubmit">
    </form>
  </div>

  <!--Category form-->
  <div class="col-4">
    <h2>Add Product Category</h2>
    <form method="post" action="" onsubmit="return valCat()" name="category" enctype="multipart/form-data">
      <input type="text" name="name" placeholder="Category name" required><br>
      <input type="file" name="category_image" required><br>
      <input type="submit" value="Submit" name="csubmit">
    </form>
  </div>

  <!--Product image form -->
  <div class="col-4">
    <h2>Add Product image</h2>
    <form method="post" action="" onsubmit="return valImage()" enctype="multipart/form-data" name="image">
      <input type="file" name="prod_image" required><br>
      <input type="text" name="title" placeholder="Image title" required><br>
      <input type="submit" value="Submit" name="isubmit">
    </form>
  </div>
</div>


<?php
//Product submission
if (isset($_POST["psubmit"])) {
  $name = $_POST["name"];
  $desc = $_POST["desc"];
  $price = $_POST["price"];
  $category_id = $_POST["category"];
  $image_id = $_POST["image"];
  $sql = "insert into product (product_name, description, price, category_id, image_id)
    values('$name', '$desc', '$price', '$category_id', '$image_id')";
    echo "<meta http-equiv='refresh' content='0'>";
    if ($conn->query($sql)) {
    echo "Success!";
  } else {
    echo "Error 😥: " . $conn->error;
  }
}

//Category submission
if (isset($_POST["csubmit"])) {
  $name = $_POST["name"];
  $category_image = addslashes(file_get_contents($_FILES["category_image"]["tmp_name"]));
  $sql = "insert into product_category (category_name, category_image)
    values('$name', '$category_image')";
    echo "<meta http-equiv='refresh' content='0'>";
    if ($conn->query($sql)) {
    echo "Success!";
  } else {
    echo "Error 😥: " . $conn->error;
  }
}

//Image submission
if (isset($_POST["isubmit"])) {
  $image = addslashes(file_get_contents($_FILES["prod_image"]['tmp_name']));
  $title = $_POST["title"];
  $sql = "insert into product_image (title, image)
    values('$title', '$image')";
    echo "<meta http-equiv='refresh' content='0'>";
  if ($conn->query($sql)) {
    echo "Success!";
  } else {
    echo "Error 😥: " . $conn->error;
  }
}



include "partials/footer.php";
?>
