watch -n 5 "php bot/checkMessages.php" &
watch -n 5 "php bot/checkPayment.php" &
watch -n 60 "php bot/pinner.php" &