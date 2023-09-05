<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RawMaterialStockExport implements FromCollection, WithHeadings {

  protected $data;

  public function __construct(Collection $d) {
      $this->data = $d;
  }
  
  public function collection() {
    return $this->data;
  }
  
  public function headings(): array {
      return [
        'Código de Insumo',
        'Descripción',
        'Marca',
        'Precio unitario',
        'Stock Total',
        'Stock Inicial',
        'Entrada',
        'Salida',
        'Stock Actual',
        'Stock Porcentaje',
        'Proveedor(es)',
        'Último abastecimiento',
      ];
  }
}
