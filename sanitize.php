<?php
function sanitize($data, $type, $conn) {
    switch ($type) {
        case 'string':
            $data = $conn->real_escape_string($data);
            $data = htmlspecialchars($data);
            break;
        case 'number':
            $data = $conn->real_escape_string($data);
            $data = filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            break;
        // Add more cases as needed
    }
    return $data;
}
?>
