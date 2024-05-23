<?php

/**
 * Class Book.
 */
class Book
{
  public string $title;

  public function getTitle(): string {
    // phpstan-ignore argument.byRef
    return $this->tilte;
  }

}
