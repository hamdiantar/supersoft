
    @php

        $barcode->setText($barcode_value);
        $barcode->setType(\CodeItNow\BarcodeBundle\Utils\BarcodeGenerator::Code128);
        $barcode->setScale(2);
        $barcode->setLabel($barcode_value);
        $barcode->setThickness(25);
        $barcode->setFontSize(10);

       $code = $barcode->generate();
    @endphp

    @for($i=0; $i<$qty; $i++)
        <div class=" text-center" style=" text-align: center;  #a1a1a1; margin-bottom: 10px; width: 50%;">
            <img src="data:image/png;base64,{{$code}}"/><br><hr>
        </div>
    @endfor

<script type="application/javascript">
    window.print();
</script>

