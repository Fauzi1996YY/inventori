<?php

/**
* Image resize and reposition is taken from:
* https://gist.github.com/benshimmin/4088493
************************************************************************/

namespace App\Core;

class Pdf extends \FPDF {
  /* For image scaling and positioning */
  private const DPI = 96;
  private const MM_IN_INCH = 25.4;
  private const A4_WIDTH = 297 + 25;
  private const A4_HEIGHT = 210;
  private const MAX_HEIGHT = 900;
  private const MAX_WIDTH = 717;

  /* For multicell table */
  private $widths;
  private $heights;

  public function __construct() {
    parent::__construct('portrait', 'mm', 'A4');
    $this->AliasNbPages();
    $this->SetAutoPageBreak(true, 10);
    $this->SetFont('Arial', '', 12);
    $this->AddPage();
    $this->SetX(10);
  }

  public function Header() {
    // $this->Cell(15, 20, '', 0, 0, 'C');
    // $this->Image(BASE_DIR . '/assets/images/logo.png', 10, null, 50, 0);
    
    // $this->SetY(5);
  }

  public function Footer() {
    $this->SetY(-10);
    $this->SetFont('Arial', 'I', 8);
    $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
  }

  /* Multicell table */
  public function SetWidths($w) {
    $this->widths = $w;
  }

  public function SetAligns($a) {
    $this->aligns = $a;
  }

  public function Row($data) {
    // Calculate the height of the row
    $nb = 0;
    for($i=0; $i < count($data); $i++) {
      $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
    }
    $h=8*$nb;

    // Issue a page break first if needed
    $this->CheckPageBreak($h);

    // Draw the cells of the row
    for($i = 0; $i < count($data); $i++) {
        
      $w = $this->widths[$i];
      $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';

      // Save the current position
      $x = $this->GetX();
      $y = $this->GetY();

      // Draw the border
      $this->Rect($x, $y, $w, $h);

      // Print the text
      $this->MultiCell($w, 8, $data[$i], 0, $a);

      // Put the position to the right of the cell
      $this->SetXY($x + $w, $y);
    }

    // Go to the next line
    $this->Ln($h);
  }

  public function CheckPageBreak($h) {
    // If the height h would cause an overflow, add a new page immediately
    if($this->GetY() + $h > $this->PageBreakTrigger) {
      $this->AddPage($this->CurOrientation);
    }
  }

  public function NbLines($w, $txt) {
    
    // Computes the number of lines a MultiCell of width w will take
    $cw = &$this->CurrentFont['cw'];

    if($w == 0) {
      $w = $this->w - $this->rMargin - $this->x;
    }
        
    $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
    $s = str_replace("\r", '', $txt);
    $nb = strlen($s);
    
    if($nb > 0 && $s[$nb-1] == "\n") {
      $nb--;
    }

    $sep = -1;
    $i = 0;
    $j = 0;
    $l = 0;
    $nl = 1;
    
    while($i < $nb) {
      
      $c = $s[$i];
      if($c == "\n") {
        $i++;
        $sep = -1;
        $j = $i;
        $l = 0;
        $nl++;
        continue;
      }

      if($c == ' ') {
        $sep = $i;
      }
       
      $l += $cw[$c];
      if($l > $wmax) {
        if($sep == -1) {
          if($i==$j) {
            $i++;
          }
        }
        else {
          $i=$sep+1;
        }
        $sep = -1;
        $j = $i;
        $l = 0;
        $nl++;
      }
      else {
        $i++;
      }
    }
    return $nl;
  }

}

?>