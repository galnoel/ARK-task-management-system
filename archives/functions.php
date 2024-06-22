<?php
// read
function read_data($query)
{

    $destination = [];
    global $conn;
    $result = mysqli_query($conn, $query);
    while ($data = mysqli_fetch_assoc($result)) {
        $destination[] = $data;
    }
    return $destination;
}

function read($id)
{
    $transaction = read_data(
        "SELECT * FROM transaction WHERE idUser='$id'"
    );

    return $transaction;
}
?>