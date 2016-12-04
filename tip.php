<html>

  <link
    rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
    crossorigin="anonymous">

  <link
    rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
    integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp"
    crossorigin="anonymous">

  <script
    src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
    crossorigin="anonymous"></script>

  <script
    src="https://code.jquery.com/jquery-3.1.1.min.js"
    integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
    crossorigin="anonymous"></script>


  <title> Tip Calculator </title>

  <body style="margin-top: 200px;">
    <div
      class="container"
      style="display:table;width:auto;margin-left:auto;margin-right:auto;">
      <div class="col-lg-10">
        <div class="clearfix m-t">
          <div class="alert alert-danger" hidden >
            <strong>Error!</strong> Please correct your input.
          </div>
          <div class="panel panel-info">
            <div class="panel-heading">
              <span class="panel-heading"> Tip Calculator </span>
            </div>
            <div class="panel-body" id="tip-calculator-body">
              <li class="list-group-item" style="display:flex;">
                <div class="input-group pull-right subtotal">
                  <span class="input-group-addon"> Bill subtotal </span>
                  <span class="input-group-addon">$</span>
                  <input id="subtotal" type="text"
                    class="form-control" value="0"/>
                </div>
              </li>
              <li class="list-group-item" style="display:flex;">
                <?php renderTipsOptions(); ?>
              </li>
              <li class="list-group-item" style="display:flex;">
                <div class="input-group pull-right subtotal">
                  <span class="input-group-addon">
                    <input type="checkbox" id="checkSplit">
                  </span>
                  <span class="input-group-addon"> Spilt </span>
                  <input disabled id="split" type="text"
                    class="form-control" value="1"/>
                </div>
              </li>
              <li
                class="list-group-item result"
                style="display:none; border-bottom: none;">
                <span class="label label-info"> Tip </span>
                <span class="pull-right resultValue" id="tipValue">
                  0.0
                </span>
              </li>
              <li
                class="list-group-item result"
                style="display:none; border-top: none;">
                <span class="label label-info"> Total </span>
                <span class="pull-right resultValue" id="totalValue">
                  0.0
                </span>
              </li>
              <li class="list-group-item clearfix" >
                <button class="btn brn-info pull-right calculate">
                  Calculate
                </button>
              </li>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>

  <script>
    function parseNumber(num) {
      return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function displayResult(data) {
      $('#tipValue').html(parseNumber(data.tip));
      $('#totalValue').html(parseNumber(data.total));
      $('.result').show();
    }

    function updateForm(data) {
      cleanForm();

      $('.warning').show();

      if (!data.validSubtotal) {
        $('#subtotal').val("0");
        $('#subtotal').parent().addClass('has-error');
      }

      if (!data.validTipRate) {
        $('#customizeTip').val("0");
        $('#customizeTip').parent().addClass('has-error');
      }

      if (!data.validSplit) {
        $('#split').val("1");
        $('#split').parent().addClass('has-error');
      }
    }

    function cleanForm() {
      $('#subtotal').parent().removeClass('has-error');
      $('#customizeTip').parent().removeClass('has-error');
      $('#split').parent().removeClass('has-error');
      $('.resultValue').val("0");
      $('.result').hide();
    }

    $('#checkSplit').on('click', function() {
      $('#split').prop('disabled', !$(this).is(':checked'));
    })

    $('.calculate').on('click', function() {
      const data = {
        'subtotal': $('#subtotal').val(),
        'tipRate':
          $('input[name=tipRate]:checked').val() == -1
            ? $('#customizeTip').val()
            : $('input[name=tipRate]:checked').val(),
        'split': $('#checkSplit').is(':checked') ? $('#split').val() : 1,
      };

      $.ajax({
        url: 'calculate.php',
        method: 'POST',
        dataType: 'json',
        data: data,
        success: function(res) {
          res.success
            ? displayResult(res)
            : updateForm(res);
        }
      });
    });
  </script>

</html>

<?php
function renderTipsOptions() {
  for ($percentage = 10; $percentage < 25; $percentage += 5) {
    print '<label class="radio-inline">
      <input type="radio" value="' . $percentage . '"name="tipRate"> ' .
      $percentage .'%</label>';
  }
  print '<label class="radio-inline" >
    <input type="radio" value="-1" name="tipRate">
    <div class="input-group">
      <input id="customizeTip" type="text" class="form-control" value="0"/>
      <span class="input-group-addon">%</span>
    </div>
    </label>';
}

?>
