<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MerchandiseClearanceExport implements FromCollection, WithHeadings {

  protected $data;

  public function __construct(Collection $d) {
      $this->data = $d;
  }
  
  public function collection() {
    return $this->data;
  }
  
  public function headings(): array {
    return [
      'Cod. Trabajo',
      'F. despacho',
      'Cliente',
      'Supervisor',
      'Recibido por',
    ];
  }
}
