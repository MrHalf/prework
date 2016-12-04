<?php

function calculateTips() {
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subtotal = isset($_POST['subtotal']) ? intval($_POST['subtotal']) : null;
    $tipRate = isset($_POST['tipRate']) ? floatval($_POST['tipRate']) : null;
    $split = isset($_POST['split']) ? intval($_POST['split']) : 1;

    $validSubtotal = !(is_null($subtotal) || $subtotal <= 0);
    $validTipRate = !(is_null($tipRate) || $tipRate < 10);
    $validSplit = $split >= 1;

    return $validSubtotal && $validTipRate && $validSplit
      ? json_encode(Array(
          'success' => true,
          'tip' => ($tip = $subtotal * $tipRate / 100.) / $split,
          'total' => ($tip + $subtotal) / $split,
        ))
      : json_encode(Array(
          'success' => false,
          'validSubtotal' => $validSubtotal,
          'validTipRate' => $validTipRate,
          'validSplit' => $validSplit,
        ));
  }
}

print calculateTips();
?>
