<div class="box-crop">
  <div class="row">
    <div class="col-md-12">
      <!-- <h3>Demo:</h3> -->
      <div class="img-container">
        <img id="image" src="" alt="Picture">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 docs-buttons">
      <div class="btn-group">
        <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;zoom&quot;, 0.1)">
            <span class="fa fa-search-plus"></span>
          </span>
        </button>
        <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;zoom&quot;, -0.1)">
            <span class="fa fa-search-minus"></span>
          </span>
        </button>
      </div>

      <div class="btn-group">
        <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;rotate&quot;, -45)">
            <span class="fa fa-rotate-left"></span>
          </span>
        </button>
        <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;rotate&quot;, 45)">
            <span class="fa fa-rotate-right"></span>
          </span>
        </button>
      </div>

     <button type="button" class="btn btn-primary" data-method="reset" title="Reset">
        <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;reset&quot;)">
          <span class="fa fa-refresh"></span>
        </span>
      </button>

      <div class="btn-group" id="crop-img-upload">
        <label class="btn btn-primary btn-upload" for="inputImage" title="Upload image file">
          <input type="file" class="sr-only" id="inputImage" name="file" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Import image with Blob URLs">
            <span class="fa fa-upload"></span>
          </span>
        </label>
      </div>
      
      <div class="btn-group btn-group-crop">
        <span type="button" id="upload_crop" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 800, &quot;height&quot;: 800 }">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;getCroppedCanvas&quot;, { width: 800, height: 800 })">
            Lưu lại
          </span>
        </span>
      </div>

      <!-- Show the cropped image in modal -->
      <div class="modal fade docs-cropped" id="getCroppedCanvasModal" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="getCroppedCanvasTitle">Cropped</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <a class="btn btn-primary" id="download" href="javascript:void(0);" download="cropped.jpg">Download</a>
              <a class="btn btn-primary" id="upto" href="javascript:void(0);" download="cropped.jpg">UP to</a>
            </div>
          </div>
        </div>
      </div><!-- /.modal -->
    </div><!-- /.docs-toggles -->
  </div>
</div>

<script type="text/javascript">
  function upfile(url_img, id) {
    jQuery.ajax({
        url: '<?= \yii\helpers\Url::to(['/product/product/uploadproductfilecrop']) ?>',
        type: 'POST',
        data: {'url_img': url_img, 'id' : id},
        // dataType: 'JSON',
        success: function (data) {
            $('#img-up-'+id).attr('src', data);
            $('.close-box-crops').click();
        }
    });
  }
</script>