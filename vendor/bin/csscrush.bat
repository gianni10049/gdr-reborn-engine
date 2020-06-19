@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../css-crush/css-crush/bin/csscrush
php "%BIN_TARGET%" %*
