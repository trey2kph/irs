<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?= $title ?></title>

    <style type="text/css">
    
        body {
            background-color: #fff;
            margin: 40px;
            font-family: Lucida Grande, Verdana, Sans-serif;
            font-size: 14px;
            color: #4F5155;
        }
        
        a {
            color: #003399;
            background-color: transparent;
            font-weight: normal;
        }
        
        h1 {
            color: #444;
            background-color: transparent;
            border-bottom: 1px solid #D0D0D0;
            font-size: 20px;
            font-weight: bold;
            margin: 24px 0 2px 0;
            padding: 5px 0 6px 0;
        }
        
        code {
            font-family: Monaco, Verdana, Sans-serif;
            font-size: 12px;
            background-color: #f9f9f9;
            border: 1px solid #D0D0D0;
            color: #002166;
            display: block;
            margin: 14px 0 14px 0;
            padding: 12px 10px 12px 10px;
        }
        
        .report_title {
            font-size: 16px;
        }
        
        .tdatareport th {
            color: #FFF;
            background-color: #666;
            border: 1px solid #333;
        }
        
        .tdatareport, .tdatareport th, .tdatareport td {
            border: 1px solid #333;
        }
        
        .tdatareport th, .tdatareport td {
            padding: 5px;
        }

        .lefttalign {
            text-align: center;
        }
        
        .centertalign {
            text-align: center;
        }
        
        .righttalign {
            text-align: right;
        }
    
    </style>
</head>
<body>

    <h1><?= $title ?></h1>    
    <p><?= $message; ?></p>

</body>
</html>