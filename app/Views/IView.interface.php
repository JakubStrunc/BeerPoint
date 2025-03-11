<?php

namespace kivweb\Views;

interface IView {
    //public function printView($templateData): string;

    public function printOutput(array  $templateData, string $pageType);

}
