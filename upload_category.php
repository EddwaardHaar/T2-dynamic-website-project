<?php
$title = "Skate Shop - Shop Page";
$stylesheet = "shop";
$extra = "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css    \">";
include "partials/header.php";

$servername = "webprog23-db-1";
$username = "root";
$password = "password";
$dbname = "skate_shop";

$conn = new mysqli($servername, $username, $password, $dbname);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>


<a href="upload_products.php">upload product</a>
<h1>Create Category</h1>
<form action="upload_category.php" method="post" enctype="multipart/form-data">
    <label for="category_name">Category Name:</label>
    <input type="text" name="category_name" id="category_name">
    <br>
    <label>Category Image:</label>
    <input type="file" name="category_image"><br><br>
    <input type="hidden" name="submitted" value="true">
    <input type="submit" name="submit" value="Add Category">
</form>

<?php
if (isset($_POST['submit'])) {

        $category_name = $_POST['category_name'];

        // Check if a file was uploaded
        if (!empty($_FILES['category_image']['tmp_name']) && is_uploaded_file($_FILES['category_image']['tmp_name'])) {

            // Read the file contents and escape special characters
            $category_image = addslashes(file_get_contents($_FILES['category_image']['tmp_name']));

            // Prepare the SQL statement to insert the new category
            $sql = "INSERT INTO product_category (category_name, category_image) VALUES ('$category_name', '$category_image')";

            // Execute the SQL statement and check for errors
            if ($conn->query($sql) === TRUE) {
                echo "New category added successfully!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

        } else {
            // No file was uploaded
            echo "Error: No category image was uploaded.";
        }

}
?>

<?php
    $sql = "SELECT * FROM product_category";
    $result = $conn->query($sql);
    
    // Check if there are any categories
    if ($result->num_rows > 0) {
        // Output data of each category
        while($row = $result->fetch_assoc()) {
            echo "Category ID: " . $row["category_id"] . "<br>";
            echo "Category Name: " . $row["category_name"] . "<br>";
            echo '<img src="data:image/jpeg;base64,'.base64_encode( $row['category_image'] ).'"/>';
            
            //update category ID and name
            echo "
            <form action='update_category.php' method='POST'>
                <input type='hidden' name='category_id' value='". $row['category_id'] ."'>
                <label for='new_category_id'>New Category ID:</label>
                <input type='text' id='new_category_id' name='new_category_id' value='". $row['category_id'] ."'><br>
                <label for='new_category_name'>New Category Name:</label>
                <input type='text' id='new_category_name' name='new_category_name' value='". $row['category_name'] ."'><br>
                <input type='submit' name='update' value='Update'>
            </form>";
            
            //delete category
            echo "
            <form action='upload_category.php' method='POST'>
            <input type='hidden' name='category_id' value='". $row['category_id'] ."'>
            <input type='submit' name='delete' value='Delete'>
            </form>";
            echo "<hr>";
            
        }
    }else {
        echo "No categories found.";
    }
    if (isset($_POST['delete'])) {
        $category_id = $_POST['category_id'];
        $query_delete = "DELETE FROM product_category WHERE category_id = $category_id";
        mysqli_query($conn, $query_delete);
        header("Location: upload_category.php");
        exit;
    }else{
        echo "cannot delete";
    }

    
    // Close the database connection
?>

<?php
    include 'partials/footer.php';
?>