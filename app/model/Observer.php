<?php

interface Observer {
    // Parameter yang diterima adalah subject yang sedang dipantau
    public function update(Subject $subject);
}