<?php
$title = "Skate Shop - Shop Page";
$stylesheet = "shop";
$extra = "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css    \">";
include "partials/header.php";

include "partials/db.php";


mysqli_select_db($conn, 'products');
$sql_product = "SELECT * FROM products";
$result_product = $conn->query($sql_product);


$sql_category = "SELECT * FROM product_category";
$result_category = $conn->query($sql_category);

?>




<div class="container category">
  <div class="col-12 p-4 breadcrumbs">
    <a href="index.php">
      Main page
    </a>
    <img src="images/Icons/ArrowRight.png" alt="Blog3">
    <a href="shop.php">
      <b>Shop</b>
    </a>
  </div>

  <?php
  $sql_items = "SELECT pc.category_id, COUNT(p.product_id) as product_count
                    FROM product_category pc
                    LEFT JOIN products p
                    ON pc.category_id = p.category_id
                    GROUP BY pc.category_id;";

  $result_items = $conn->query($sql_items);

  ?>


  <div class="row d-flex justify-content-center justify-content-md-start">
    <?php
    while ($row = $result_category->fetch_assoc()):
      ?>
        <div class="card col-4 text-md catcard">
          <a href="shop.php?link=<?php echo $row['category_id']; ?>">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['category_image']); ?>" class="card-img"
              alt="<?php echo $row['category_name']; ?> ">
            <div class="card-img-overlay">
              <h5 class="card-title card-title-category">
                <?php echo $row['category_name']; ?>
              </h5>
              <p class="card-text"><small>
                  <?php

                  if (!$result_items) {
                    die("Query failed: " . $conn->error);
                  }

                  if ($result_items->num_rows > 0) {
                    $row = $result_items->fetch_assoc();
                    $product_count = $row["product_count"];


                  } else if ($result_items->num_rows <= 0) {
                    $product_count = 0;
                  }
                  echo $product_count; ?> items
                </small></p>
            </div>
          </a>
        </div>
    <?php endwhile; ?>

    <?php
    if (isset($_GET["link"])) {
      $cat = $_GET["link"];
      $sql_product .= " WHERE category_id = $cat";
      $result_product = $conn->query($sql_product);
    }

    ?>

  </div>
</div>
</div>

<hr style="margin: auto;">

<div class="container justify-content-center">
  <form method="post">
    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="sorting"
      onchange="this.form.submit()">
      <option selected>Sorting</option>
      <option value="1">Lowest price first</option>
      <option value="2">Highest price first</option>
      <option value="3">Name [A-Z]</option>
      <option value="4">Name [Z-A]</option>
    </select>
  </form>
  <div class="row">
    <?php
    $query = $sql_product;
    if (isset($_POST['sorting'])) {
      $sorting = $_POST['sorting'];
      if ($sorting == 1) {
        $sql_product = $query . " ORDER BY price ASC";
      } else if ($sorting == 2) {
        $sql_product = $query . " ORDER BY price DESC";
      } else if ($sorting == 3) {
        $sql_product = $query . " ORDER BY product_name ASC";
      } else if ($sorting == 4) {
        $sql_product = $query . " ORDER BY product_name DESC";
      }
      $result_product = $conn->query($sql_product);
    }
    ?>
    <div class="row">
      <div class="row">



        <?php
        while ($row = $result_product->fetch_assoc()):

          ?>
          <div class="col-xl-3 col-md-4 d-flex justify-content-xl-start justify-content-center">
            <div class="card products">
              <a
                href="product.php?link=<?php echo $row['product_id']; ?>&name=<?php echo $row['product_name']; ?>&price=<?php echo $row['price']; ?>&discount=<?php echo $row['discount']; ?>">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image']); ?>" class="card-img-top"
                  alt="<?php echo $row['product_name']; ?>" height='175.91' width='280' />
                <div class="card-body">
                  <h5 class="card-title">
                    <?php echo $row['product_name']; ?>
                  </h5>
                  <p class="price">Price</p><br>
                  <p class="fw-bold" style="color:#275A53;">
                    <?php echo $row['price']; ?>€ <del style="color: black;">
                      <?php echo $row['discount']; ?>€
                    </del>
                  </p>
                  <a href="product.php" class="btn btn-danger btn-circle"><i
                      class="fa-solid fa-shopping-bag fa-md"></i></a>
                </div>
              </a>
            </div>
          </div>

        <?php endwhile; ?>
      </div>
    </div>
  </div>
</div>

<?php
include 'partials/footer.php';
?>