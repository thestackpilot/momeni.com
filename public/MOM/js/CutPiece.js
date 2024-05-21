class ShowCutPiece {
    constructor() {
        let coordinates = {x: 0, y: 0};
        let rectangleData = null;
        let totalFrameWidth = 0;
        let totalFrameHeight = 0;
        let frameOrientation = 'V';
        let labelH = 'Width';
        let labelV = 'Height';
        let ratio = 5;
        let ratioX = 5;
        let ratioY = 5;
        let size = {width: 0, height: 0};
        let VerticalTextWidth = 30;
        let rectangles = [];
        let rectangle = 'broadloom_cut_pieces';

        /* setters starts */
        this.setCoordinates = function (value) {
            coordinates = value;
        };
        this.setRectangleData = function (data) {
            rectangleData = data;
        }
        this.setTotalFrameWidth = function (value) {
            totalFrameWidth = value;
        }
        this.setTotalFrameHeight = function (value) {
            totalFrameHeight = value;
        }
        this.setFrameOrientation = function (value) {
            frameOrientation = value;
        }
        this.setLabelH = function (value) {
            labelH = value;
        }
        this.setLabelV = function (value) {
            labelV = value;
        }
        this.setRatio = function (value) {
            ratio = value;
        }
        this.setRatioX = function (value) {
            ratioX = value;
        }
        this.setRatioY = function (value) {
            ratioY = value;
        }
        this.setSize = function (value) {
            size = value;
        }
        this.setRectangles = function (data) {
            rectangles = data;
        }

        /* setters ends */

        /* getter starts */
        this.getRectangleData = function () {
            return rectangleData;
        }
        this.getCoordinates = function () {
            return coordinates;
        };
        this.getTotalFrameWidth = function () {
            return totalFrameWidth;
        };
        this.getTotalFrameHeight = function () {
            return totalFrameHeight;
        };
        this.getFrameOrientation = function () {
            return frameOrientation;
        }
        this.getLabelH = function () {
            return labelH;
        }
        this.getLabelV = function () {
            return labelV;
        }
        this.getRatio = function () {
            return ratio;
        }
        this.getRatioX = function () {
            return ratioX;
        }
        this.getRatioY = function () {
            return ratioY;
        }
        this.getSize = function () {
            return size;
        }
        this.getVerticalTextWidth = function () {
            return VerticalTextWidth;
        }
        this.getRectangles = function () {
            return rectangles;
        }
        this.getRectangle = function () {
            return rectangle;
        }

        /* getter ends */
    }

    cutPiecesInitilize(coordinates, payload) {
        this.setCoordinates(coordinates);
        $('#cut-pieces').html('');

        $.ajax({
            url: "/get-cut-pieces",
            type: "GET",
            data: payload,
            success: (response) => {
                if (response.Success) {
                    this.setRectangleData(response);
                    let orientation = this.getFrameOrientation();
                    this.GetFrameDimensions(orientation);

                    if (orientation === '') {
                        this.SetDefaultOrientation();
                    }

                    if (orientation === 'V') {
                        this.PrepareRectangleWV();
                    } else {
                        this.PrepareRectangleWH();
                    }

                    this.renderRectangles()
                } else {
                    toastr.error('Something went wrong', {
                        hideDuration: 10000,
                        closeButton: true,
                    });
                }
            }
        })
    }

    GetFrameDimensions(frame_orientation) {
        var rectangleOnXon0;
        this.setTotalFrameWidth(0);
        this.setTotalFrameHeight(0);
        var frameWidth = 0;
        var frameHeight = 0;
        var data = this.getRectangleData();

        if (!data || !Array.isArray(data.ShowCuts) || data.ShowCuts.length === 0) {
            console.error('Invalid or empty data.ShowCuts array');
            return;
        }

        if (frame_orientation === 'V') {
            frameHeight = Math.max(...data.ShowCuts.map(o => parseFloat(o.TotalWidth)));
            this.setTotalFrameHeight(frameHeight);

            rectangleOnXon0 = $.grep(data.ShowCuts, function (i) {
                return i.UsedWidth == 0;
            });

            $.each(rectangleOnXon0, function (i, rect) {
                frameWidth += rect.ATSLength;
            });
            this.setTotalFrameWidth(frameWidth);
        } else {
            frameWidth = Math.max(...data.ShowCuts.map(o => parseFloat(o.TotalWidth)));
            this.setTotalFrameWidth(frameWidth);

            rectangleOnXon0 = $.grep(data.ShowCuts, function (i) {
                return i.UsedWidth == 0;
            });

            $.each(rectangleOnXon0, function (i, rect) {
                frameHeight += rect.ATSLength;
            });
            this.setTotalFrameHeight(frameHeight);
        }
    }

    SetDefaultOrientation() {
        var height = this.getTotalFrameHeight();
        var width = this.getTotalFrameWidth();
        if (height > width) {
            this.setFrameOrientation('V');
        } else {
            this.setFrameOrientation('H');
        }
    }

    PrepareRectangleWH() {
        this.setLabelH('Width');
        this.setLabelV('Length');

        var rectangles = [];
        var size = {width: 0, height: 0};
        var rect;
        var data = this.getRectangleData();
        var orientation = this.getFrameOrientation();
        var ratio = this.getRatio();

        this.GetFrameDimensions(orientation);
        this.CalculateRatio();

        data.ShowCuts.forEach((object) => {
            rect = {};
            rect.CLengthID = object.CLengthID;
            rect.color = object.LengthStatus == 'F' ? '#337ab7' : '#d9534f';

            rect.x = Math.round(object.UsedWidth * ratio);
            rect.y = Math.round(object.UsedLength * ratio);
            rect.width = Math.round(object.ATSWidth * ratio);
            rect.height = Math.round(object.ATSLength * ratio);

            rect.text = {};
            rect.text.label = this.getFormattedItemSize(object.ATSWidth, 'Inch') + ' X ' + this.getFormattedItemSize(object.ATSLength, 'Inch');
            rect.text.x = parseInt(rect.width / 2);
            rect.text.y = parseInt(rect.height / 2);

            if (object.UsedLength == 0)
            {
                size.width += rect.width;
            }

            if (object.UsedWidth == 0)
            {
                size.height += rect.height;
            }

            rectangles.push(rect);
        });

        size = { width: (size.width + this.getVerticalTextWidth()) + 'px', height: size.height + 'px' };
        this.setSize(size);
        this.setRectangles(rectangles);
    }

    PrepareRectangleWV() {
        this.setLabelV('Width');
        this.setLabelH('Length');

        var rectangles = [];
        var size = {width: 0, height: 0};
        var rect;
        var data = this.getRectangleData();
        var orientation = this.getFrameOrientation();
        var ratio = this.getRatio();

        this.GetFrameDimensions(orientation);
        this.CalculateRatio();

        data.ShowCuts.forEach((object) => {
            rect = {};
            rect.CLengthID = object.CLengthID;
            rect.color = object.LengthStatus == 'F' ? '#337ab7' : '#d9534f';

            rect.x = Math.round(object.UsedLength * ratio);
            rect.y = Math.round(object.UsedWidth * ratio);
            rect.width = Math.round(object.ATSLength * ratio);
            rect.height = Math.round(object.ATSWidth * ratio);

            rect.text = {};
            rect.text.label = this.getFormattedItemSize(object.ATSWidth, 'Inch') + ' X ' + this.getFormattedItemSize(object.ATSLength, 'Inch');
            rect.text.x = parseInt(rect.width / 2);
            rect.text.y = parseInt(rect.height / 2);

            if (object.UsedLength == 0)
            {
                size.height += rect.height;
            }

            if (object.UsedWidth == 0)
            {
                size.width += rect.width;
            }

            rectangles.push(rect);
        });

        size = { width: (size.width + this.getVerticalTextWidth()) + 'px', height: size.height + 'px' };
        this.setSize(size);
        this.setRectangles(rectangles);
    }

    CalculateRatio() {
        var orientation = this.getFrameOrientation();
        var ratio;
        var height = this.getTotalFrameHeight();
        var width = this.getTotalFrameWidth();

        if (orientation === 'V') {

            if (height > 133) {
                ratio = parseFloat(parseFloat(400 / height).toFixed(2));
            } else {
                if (width < 200)
                {
                    ratio = 3;
                } else if (width < 300) {
                    ratio = 2;
                } else {
                    ratio = parseFloat(parseFloat(900 / width).toFixed(2));
                }
            }
        } else {
            if (width > 300) {
                ratio = parseFloat(parseFloat(900 / width).toFixed(2));
            }
            else
            {
                if (height < 100)
                {
                    ratio = 3;
                }
                else if (height < 150)
                {
                    ratio = 2;
                }
                else
                {
                    ratio = parseFloat(parseFloat(400 / height).toFixed(2));
                }
            }
        }

        this.setRatio(ratio);
    }

    renderRectangles() {
        var size = this.getSize();
        var totalFrameWidth = this.getTotalFrameWidth();
        var totalFrameHeight = this.getTotalFrameHeight();
        var labelH = this.getLabelH();
        var labelV = this.getLabelV();
        var VerticalTextWidth = this.getVerticalTextWidth();
        var Rectangle = this.getRectangle();

        var html = '<div class="popup" id="parent_PopupContainer">' +
            '<div class="container remove-padding h-100 popuptext" id="boradloom_cutpieces" style="width:' + size.width + '">' +
            '<div class="row justify-content-md-center" style="background-color:lightgray;position: relative;">' +
            '<div class="col-lg-12 text-center">' + this.getFormattedItemSize(totalFrameWidth, 'Inch') + ' (' + labelH + ')</div>' +
            '</div>' +

            '<div class="row h-100">' +
            '<div class="col-lg-1 text-center remove-padding" id="_VerticalText" style="background-color:lightgray; writing-mode: vertical-rl; width: ' + VerticalTextWidth + 'px; height:' + size.height + '"><span style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;">' + this.getFormattedItemSize(totalFrameHeight, 'Inch') + ' (' + labelV + ')</span></div>' +
            '<div class="col-lg-1 remove-padding" id="child_' + Rectangle + '"></div>' +
            '</div>' +
            '</div>' +
            '</div>';

        $('#cut-pieces').append(html);

        var rectangles = this.getRectangles();
        rectangles.forEach((rect, i) =>
        {
            this.DrawRectangle(rect, i);
        });
    }

    DrawRectangle(rectangle, id) {

        var rectDiv = '<div id="child' + '_' + id + '" tagId="' + rectangle.CLengthID + '" style="height: ' + rectangle.height + 'px; width: ' + rectangle.width + 'px; border-style: solid; border-width: 1px' +
            ';border-color:' + rectangle.color +
            ';background:white' +
            ';position:absolute' +
            ';left:' + rectangle.x + 'px' +
            ';top:' + rectangle.y + 'px;"' +
            ' data-toggle="tooltip" title="' + rectangle.text.label.replace('"', '&quot;') + '">' +
            '<span tagId="' + rectangle.CLengthID + '" style="' + (rectangle.width > 20 && rectangle.width < 55 && rectangle.height > 100 ? "writing-mode: vertical-rl;display:flex;align-items:center;height:fit-content;" : "display:block;width:100%;text-align:center;") + 'font-size:10px;position:absolute;left: 50%;top: 50%;-webkit-transform: translate(-50%, -50%);transform: translate(-50%, -50%);">' +
            (rectangle.width > 20 && rectangle.height > 60 ? rectangle.text.label : (rectangle.width > 60 && rectangle.height > 20 ? rectangle.text.label : "")) +
            '</span>' +

            '</div >';

        var Rectangle = this.getRectangle();
        $('#child_' + Rectangle).append(rectDiv);
    }

    getFormattedItemSize(size, format)
    {
        if (format === 'Inch')
        {
            var feet = parseInt(Math.floor(size / 12));
            var inch = size - (feet * 12);

            return feet + '\'-' + inch + '"';
        }

        return size + ' ' + format;
    }
}

