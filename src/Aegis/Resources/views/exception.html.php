<html>
    <head>
        <title><?=$exception->getExceptionType()?> - Aegis</title>
    </head>
    <body style="margin: 0; padding: 0;">
        <div style="font-size: 15px; font-family: sans-serif;">
            <!-- Header -->
            <div style="background-color: #eee; padding: 15px 25px; margin-bottom: 15px;">
                <div style="width: 150px;">
                    <?php require __DIR__.'/../svg/logo.svg'; ?>
                </div>
            </div>
            <!-- Exception content -->

            <div style="display: flex;">

                <div style="width: 25%;">

                    <?php foreach (array_reverse($exception->getTrace()) as $trace) { ?>

                        <div style="padding: 10px 25px; color: #222944; border-bottom: 1px solid #cccccc;">
                            <div style="font-weight: bolder; color: #2962ff; margin-bottom: 5px;">
                                <?=$trace['function']?>
                            </div>
                            <div style="word-break: break-all;">
                                <div style="font-size: 12px; color: #656565; margin-bottom: 5px;"><?=dirname($trace['file'])?></div>
                                <div style="font-size: 13px;">
                                    <?=basename($trace['file'])?><span style="color: #2962ff; font-weight: bolder;">:<?=$trace['line']?></span>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                </div>

                <div style="width: 75%; padding: 25px; padding-left: 50px;">
                    <div style="color: #2962ff; font-weight: bolder; font-size: 25px;">
                        <?=$exception->getExceptionType()?>
                    </div>
                    <div style="color: #222944; font-weight: lighter; border-bottom: 4px solid #cccccc; font-size: 35px; padding-bottom: 15px; margin-bottom: 15px;">
                        <?=$exception->getExceptionMessage()?>
                    </div>
                    <div>
                        <?=$exception->getExceptionDetail()?>
                    </div>
                </div>

            </div>

        </div>
    </body>
</html>