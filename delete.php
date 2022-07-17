<?php
    $Id = $_GET["id"];
    if (isset($_GET["cabang"])) {
        $Branch = $_GET["cabang"];
    } else {
        $Branch = "";
    }
    $Type = $_GET["type"];

    function hapus($Id, $Type, $Branch) {
        $connect = mysqli_connect("localhost", "root", "", "vast_retail");
        $query = "";

        if ($Type == "suppliers") {
            $query = "DELETE FROM address WHERE IdToko = $Id AND Jenis = 'supplier'";
            mysqli_query($connect, $query);
            $query = "UPDATE produk SET IdSupplier = 0 WHERE IdSupplier = $Id";
            mysqli_query($connect, $query);
            $query = "DELETE FROM suppliers WHERE Id = $Id";
        } else if ($Type == "branches") {
            $query = "DELETE FROM address WHERE IdToko = $Id AND Jenis = 'branch'";
            mysqli_query($connect, $query);
            $query = "UPDATE inventory SET IdCabang = 0 WHERE IdCabang = $Id";
            mysqli_query($connect, $query);
            $query = "DELETE FROM branch WHERE Id = $Id";
        } else if ($Type == "products") {
            $query = "DELETE FROM produk WHERE KodeProduk = $Id";
        } else if ($Type == "inventory") {
            $query = "DELETE FROM inventory WHERE KodeProduk = $Id AND IdCabang = $Branch";
        }

        mysqli_query($connect, $query);

        return mysqli_affected_rows($connect);
    }
?>

<?php
    if (hapus($Id, $Type, $Branch) > 0) {
?>
        <script>
            document.location.href = '<?php echo $Type ?>.php';
            alert('Data deleted successfully.');
        </script>
<?php    
    }
?>