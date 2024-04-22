<?php
session_start();
echo isset ($_SESSION["user_data"]) ? $_SESSION["user_data"]["id"] : null;