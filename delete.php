<?php
    $Id = $_GET["id"];
    $Type = $_GET["type"];

    function hapus($Id, $Type) {
        $connect = mysqli_connect("localhost", "root", "", "vast_retail");
        $query = "";

        if ($Type == "suppliers") {
            $query = "DELETE FROM address WHERE IdToko = $Id AND Jenis = 'supplier'";
            mysqli_query($connect, $query);
            $query = "DELETE FROM supplier WHERE Id = $Id";
        }
        mysqli_query($connect, $query);

        return mysqli_affected_rows($connect);
    }
?>

<?php
    if (hapus($Id, $Type) > 0) {
?>
        <script>
            document.location.href = '<?php echo $Type ?>.php';
            alert('Data deleted successfully.');
        </script>
<?php    
    }
?>