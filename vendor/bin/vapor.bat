@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../laravel/vapor-cli/vapor
php "%BIN_TARGET%" %*
