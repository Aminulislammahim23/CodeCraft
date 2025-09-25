<?php
require_once('db.php');

function getAnnualRevenue($year) {
    $con = getConnection();
    $sql = "SELECT r.payment_date AS date,
                   c.title AS course_name,
                   u.username AS student_name,
                   r.final_amount AS amount,
                   r.discount,
                   r.original_price,
                   r.promo_code,
                   r.payment_method,
                   r.payment_status,
                   r.transaction_id
            FROM revenue r
            JOIN courses c ON r.course_id = c.course_id
            JOIN users u ON r.user_id = u.id
            WHERE YEAR(r.payment_date) = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}
?>

