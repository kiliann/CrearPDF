<?php
require_once "fpdf/fpdf.php";
require_once "Conexion.php";

class PDF extends FPDF {

    //Aqui generamos la conexión en el construsctor
public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
{
    $this->conexion = new Conexion();
    parent::__construct($orientation, $unit, $size);
}

// Cargar los datos
    function LoadData()
    {

        $consulta ="SELECT * FROM empleado WHERE 1";

        $resultado = $this->conexion->consultas($consulta);
        $data = array();
        //Rellenamos los datos de la base de datos para luego leer el array para mostrarlos
        while ($fila = $this->conexion->extraerFila($resultado)){
            $data[]= array($fila['DNI'], $fila['Nombre'],$fila['Correo'],$fila['Telefono']);
        }


        return $data;
    }

// Tabla simple
// esto es una tabla donde mostramos un aspecto basico de una tabla mostrando la caja y su contenido
    function BasicTable($header, $data)
    {
        // Anchuras de las columnas
        $w = array(40, 35, 70, 40);
        // Cabecera
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'C');
        $this->Ln();
        // Datos
        foreach($data as $row)
        {

            $this->Cell($w[0],6,$row[0],'LR');
            $this->Cell($w[1],6,$row[1],'LR');
            $this->Cell($w[2],6,$row[2],'LR',0,'R');
            $this->Cell($w[3],6,$row[3],'LR',0,'R');
            $this->Ln();
        }
        $this->Cell(array_sum($w),0,'','T');

    }

// Una tabla más completa
//Tenemos todos los bordes de la tabla y definidas todas la celdas
    function ImprovedTable($header, $data)
    {
        // Anchuras de las columnas
        $w = array(40, 35, 70, 40);
        // Cabeceras
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'C');
        $this->Ln();
        // Datos
        foreach($data as $row)
        {

            $this->Cell($w[0],6,$row[0],'LRTB');
            $this->Cell($w[1],6,$row[1],'LRTB');
            $this->Cell($w[2],6,$row[2],'LRTB',0,'R');
            $this->Cell($w[3],6,$row[3],'LRTB',0,'R');
            $this->Ln();
        }
        // Línea de cierre
        $this->Cell(array_sum($w),0,'','T');
    }

// Tabla coloreada aqui creamos la funcion donde
// se va a mostrar las filas con colores alternando filas.
    function FancyTable($header, $data)
    {

        // Colores, ancho de línea y fuente en negrita
        $this->SetFillColor(255,0,0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('','B');
        // Cabecera
        $w = array(40, 35, 70, 40);
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $this->Ln();
        // colores y fuentes por defecto
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Datos
        $fill = false;
        foreach($data as $row)
        {
            $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
            $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
            $this->Cell($w[2],6,$row[2],'LR',0,'R',$fill);
            $this->Cell($w[3],6,$row[3],'LR',0,'R',$fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Línea de cierre
        $this->Cell(array_sum($w),0,'','T');
    }
}

$pdf = new PDF();
// Títulos de las columnas
$header = array('DNI', 'Nombre', 'Correo', 'Telefono');
// Carga de datos
$data = $pdf->LoadData();

//Aqui definimos el tipo de letra del titulo
$pdf->SetFont('Arial','B',24);
$pdf->SetTitle("Tipos de Tablas");
$pdf->AddPage();
//Posicion del titulo
$pdf->SetX(75);
$pdf->Write(20, utf8_decode("Diseños de Tablas"));
//tipo de letra de la tabla
$pdf->SetFont('Arial','',14);
//salto de linea
$pdf->ln(20);
$pdf->cell(80,15,"Datos de los empleados de la empresa" );
//salto de linea
$pdf->ln(15);
//ejecutamos el metodo de la clase
$pdf->BasicTable($header,$data);

$pdf->AddPage();
//Aqui definimos el tipo de letra del titulo de la pagina
$pdf->SetFont('Arial','B',24);
//Posicion del titulo
$pdf->SetX(75);
//tipo de letra de la tabla
$pdf->Write(20, utf8_decode("Diseños de Tablas"));
$pdf->SetFont('Arial','',14);
//salto de linea
$pdf->ln(20);
$pdf->cell(80,15,"Datos de los empleados de la empresa" );
//salto de linea
$pdf->ln(15);
//ejecutamos el metodo de la clase
$pdf->ImprovedTable($header,$data);

$pdf->AddPage();
//Aqui definimos el tipo de letra del titulo de la pagina
$pdf->SetFont('Arial','B',24);
//Posicion del titulo
$pdf->SetX(75);
$pdf->Write(20, utf8_decode("Diseños de Tablas"));
//tipo de letra de la tabla
$pdf->SetFont('Arial','',14);
//salto de linea
$pdf->ln(20);
$pdf->cell(80,15,"Datos de los empleados de la empresa" );
//salto de linea
$pdf->ln(15);
//ejecutamos el metodo de la clase
$pdf->FancyTable($header,$data);

$pdf->SetAuthor("kilian Benanavente ortega");
$pdf->Output();
?>
