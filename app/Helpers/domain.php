<?php

/**
 * Get the local host IP.
 *
 * @return string|null
 */
function getHostIp()
{
    $hostName = str_replace(['http://', 'https://'], '', config('app.url'));

    $hostIp = gethostbyname($hostName);

    // Check if the host IP is not empty & returns an actual IP address
    if ($hostIp != $hostName) {
        return $hostIp;
    }

    return request()->server('SERVER_ADDR');
}

/**
 * Get the remote host IP.
 *
 * @param $hostName
 * @return string
 */
function getRemoteIp($hostName)
{
    $remoteIp = gethostbyname($hostName);

    // Check if the host IP is not empty & returns an actual IP address
    if ($remoteIp != $hostName) {
        return $remoteIp;
    }

    return false;
}