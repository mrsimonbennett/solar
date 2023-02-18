
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="http://solar.test/build/assets/app-ef4abefe.css">


</head>
<body>
<div class="w-full h-full px-8 py-12">

    <h1>Daily Solar Estimate - Updated</h1>

    <h2>{{$estimate->today_watt_hours_day}} wH</h2>

    <p>Peak Power: {{$estimate->getPeak()['watts']}} wH @ {{$estimate->getPeak()['datetime']}}</p>

    {{-- Just embedding this image in the content here --}}
    <img src="{{ $message->embedData($estimate->generateChartWatts(), 'example-image.jpg') }}">
    <img src="{{ $message->embedData($estimate->generateChartPower(), 'power.jpg') }}">


    Thanks,<br>
  Simon

</div>
</body>
</html>
