<?php

function menu_active(string $segment): string
{
    return service('uri')->getSegment(1) === $segment
        ? 'active'
        : '';
}