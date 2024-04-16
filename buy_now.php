<?php
include 'includes/session.php';
include './includes/req_start.php';
if ($req_per == 1) {
    $redirect = 0;
    $id = $_SESSION['vm_id'];
    //Sanitizing inputs.
    if ($id > 0) {
        $conn = $pdo->open();
        $stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM orders WHERE orders_user_id=:id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if ($row['numrows'] > 0) {
            $_SESSION['error'] = 'You have already placed order. To see go to orders history.';
        } else {
            $flag = 0;
            $stmt_semopher = $conn->prepare("SELECT * FROM semopher WHERE semopher_id=:semopher");
            $stmt_semopher->execute(['semopher' => 1]);
            foreach ($stmt_semopher as $row_semopher) {
                $lock = $row_semopher['semopher_value'];
                if ($lock == 0) {
                    $stmt_semopher = $conn->prepare("UPDATE semopher SET semopher_value=:semopher WHERE semopher_id=:semopher_id");
                    $stmt_semopher->execute(['semopher' => 1, 'semopher_id' => 1]);
                }
            }
            if ($lock == 0) {
                $total = 0;
                $stmt_check = $conn->prepare("SELECT * FROM cart left join display_items on display_spring_id=cart_spring_id left join items on items_id=display_items_id WHERE cart_user_id=:cart_user_id");
                $stmt_check->execute(['cart_user_id' => $id]);
                foreach ($stmt_check as $row_check) {
                    $flag++;
                    if ($row_check['display_items_qty'] >= $row_check['cart_qty']) {
                        if ($flag == 1) {
                            $qty_array = $row_check['cart_qty'];
                            $item_array = $row_check['display_id'];
                            $cost_array = $row_check['items_cost'];
                        } else {
                            $qty_array .= ',' . $row_check['cart_qty'];
                            $item_array .= ',' . $row_check['display_id'];
                            $cost_array .= ',' . $row_check['items_cost'];
                        }
                        $total += $row_check['cart_qty'] * $row_check['items_cost'];
                    } else {
                        $pdo->close();
                        $_SESSION['error'] = 'Try Agian.';
                        header('location: MyCart');
                        exit();
                    }
                }
                if ($flag == 0)
                    $_SESSION['error'] = 'Cart Is Empty.';
                else {
                    $stmt_user = $conn->prepare("SELECT * FROM users WHERE user_id=:id");
                    $stmt_user->execute(['id' => $id]);
                    foreach ($stmt_user as $row_user)
                        if ($row_user['user_amount'] >= $total) {
                            $redirect = 1;

                            $update_qty = explode(',', $qty_array);
                            $update_items = explode(',', $item_array);
                            $i = 0;
                            foreach ($update_items as $dis_id) {
                                if (!empty($dis_id)) {
                                    $stmt_display = $conn->prepare("SELECT * FROM display_items WHERE display_id=:dis_id");
                                    $stmt_display->execute(['dis_id' => $dis_id]);
                                    foreach ($stmt_display as $row_display)
                                        $rem_qty = $row_display['display_items_qty'] - $update_qty[$i];
                                    $stmt_display_update = $conn->prepare("UPDATE display_items SET display_items_qty=:rem_qty WHERE display_id=:dis_id");
                                    $stmt_display_update->execute(['rem_qty' => $rem_qty, 'dis_id' => $dis_id]);
                                }
                                $i++;
                            }
                            date_default_timezone_set('Asia/Kolkata');
                            $today = date('Y-m-d h:i:s a');
                            function getName($n)
                            {
                                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                $randomString = '';
                                for ($i = 0; $i < $n; $i++) {
                                    $index = rand(0, strlen($characters) - 1);
                                    $randomString .= $characters[$index];
                                }
                                return $randomString;
                            }
                            $orders_id = getName(15);
                            $stmt = $conn->prepare("INSERT INTO orders (orders_id,orders_qty,orders_cost,orders_items,orders_user_id,orders_date) VALUES (:orders_id,:orders_qty,:orders_cost,:orders_items,:orders_user_id,:orders_date)");
                            $stmt->execute(['orders_id' => $orders_id, 'orders_qty' => $qty_array, 'orders_cost' => $cost_array, 'orders_items' => $item_array, 'orders_user_id' => $id, 'orders_date' => $today]);
                            $stmt = $conn->prepare("DELETE FROM cart WHERE cart_user_id=:id");
                            $stmt->execute(['id' => $id]);
                            $balance = $row_user['user_amount'] - $total;
                            $stmt_user_update = $conn->prepare("UPDATE users SET user_amount=$balance WHERE user_id=$id");
                            $stmt_user_update->execute();
                            $stmt = $conn->prepare("INSERT INTO transaction (transaction_order,transaction_user_id,transaction_send_to,transaction_amount,transaction_added_by,transaction_type,transaction_date) VALUES (:transaction_order,:transaction_user_id,:transaction_send_to,:transaction_amount,:transaction_added_by,:transaction_type,:transaction_date)");
                            $stmt->execute(['transaction_order' => $orders_id, 'transaction_user_id' => $id, 'transaction_send_to' => 'Ordered', 'transaction_amount' => -$total,  'transaction_added_by' => $id, 'transaction_type' => 1, 'transaction_date' => $today]);
                        } else
                            $_SESSION['error'] = 'Insufficient Balance.';
                    $stmt_semopher = $conn->prepare("UPDATE semopher SET semopher_value=:semopher WHERE semopher_id=:semopher_id");
                    $stmt_semopher->execute(['semopher' => 0, 'semopher_id' => 1]);
                }
            }
        }
    } else {
        $_SESSION['error'] = 'Wrong Inputs.';
    }
}
$pdo->close();
if ($redirect == 1 && isset($redirect))
    header('location: vend_now.php');
else
    header('location: MyCart');
