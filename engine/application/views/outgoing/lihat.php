<?php if (!isset($error)): ?>
<input type="hidden" id="mail-id" name="mail_id" value="<?php echo $mail->id; ?>" />
<input type="hidden" id="mail-type" name="mail_type" value="<?php echo MAIL_OUTGOING; ?>" />
<input type="hidden" id="user-id" name="user_id" value="<?php echo $me->id; ?>" />

<div class="row">
    <div class="col-lg-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nomor Surat</th>
                    <th>Pengirim</th>
                    <th>Penerima</th>
                    <th>Perihal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $mail->nomor_surat; ?></td>
                    <td><?php echo $mail->pengirim; ?></td>
                    <td><?php echo $mail->penerima; ?></td>
                    <td><?php echo $mail->perihal; ?></td>
                    <td><?php echo outgoing_status($mail->status); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="widget">
    <div class="widget-header">
        <h3><i class="fa fa-list"></i> Isi Surat</h3>
        <div class="btn-group widget-header-toolbar">
            <a href="#" id="btn-print" class="btn-borderless" title="Print to word"><i class="fa fa-print"></i></a>
            <a href="#" title="Expand/Collapse" class="btn-borderless btn-toggle-expand"><i class="fa fa-chevron-up"></i></a>
            <a href="#" title="Remove" class="btn-borderless btn-remove"><i class="fa fa-times"></i></a>
        </div>
    </div>
    <div class="widget-content">
        <div id="container-isisurat" class="row">
            <div class="col-sm-12">
                <div class="well well-sm" style="background-color: white;">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo $mail->isi_surat; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php if ($mail->dokumen): ?>
            <div class="btn-group btn">
                <button type="button" class="btn btn-link dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Download"><i class="fa fa-download"></i> Dokumen Lampiran <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <?php foreach ($mail->dokumen as $doc): ?>
                        <li><a href="<?php echo $doc->download_url; ?>" target="blank"><?php echo $doc->orig_name; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="widget">
    <div class="widget-header">
        <h3><i class="fa fa-list"></i> Histori Persetujuan Surat</h3>
        <div class="btn-group widget-header-toolbar">
            <a href="#" title="Expand/Collapse" class="btn-borderless btn-toggle-expand"><i class="fa fa-chevron-up"></i></a>
            <a href="#" title="Remove" class="btn-borderless btn-remove"><i class="fa fa-times"></i></a>
        </div>
    </div>
    <div class="widget-content">
        <div id="container-history" class="row">
            <div class="col-lg-12">
                <table id="myDataTable" class="table table-striped small">
                    <thead>
                        <tr>
                            <th style="width: 120px;">Pengirim</th>
                            <th style="width: 120px;">Tgl. kirim</th>
                            <th>Keterangan</th>
                            <th style="width: 120px;">Penerima</th>
                            <th style="width: 120px;">Tgl. Terima</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<div class="alert alert-warning" role="alert"><?php echo $error; ?></div>
<?php endif; ?>

<script type="text/javascript">
    var Disposisi = {
        mailId: 0,
        mailType: null,
        userId: 0,
        init: function(){
            var _this = this;
            _this.mailId = parseInt($('#mail-id').val());
            _this.mailType = $('#mail-type').val();
            _this.userId = parseInt($('#user-id').val());
            
            /******  WIDGET ONLY *******/
            $('#btn-print').on('click', function(){
                $.ajax({
                    type: 'POST',
                    url: '<?php echo get_action_url('service/export/word'); ?>',
                    data: {mail_id:_this.mailId,mail_type:_this.mailType},
                    dataType: 'json'
                }).then(function(data){
                    if (data.status){
                        var wnd = window.open(data.download_url);
                    }else{
                        alert(data.message);
                    }
                });
            });
            $('.widget .btn-remove').click(function(e){
                e.preventDefault();
                $(this).parents('.widget').fadeOut(300, function(){
                    $(this).remove();
                });
            });
            var affectedElement = $('.widget-content');
            $('.widget .btn-toggle-expand').clickToggle(
                function (e) {
                    e.preventDefault();

                    // if has scroll
                    if ($('.slimScrollDiv').length > 0) {
                        affectedElement = $('.slimScrollDiv');
                    }

                    $(this).parents('.widget').find(affectedElement).slideUp(300);
                    $(this).find('i.fa-chevron-up').toggleClass('fa-chevron-down');
                },
                function (e) {
                    e.preventDefault();

                    // if has scroll
                    if ($('.slimScrollDiv').length > 0) {
                        affectedElement = $('.slimScrollDiv');
                    }

                    $(this).parents('.widget').find(affectedElement).slideDown(300);
                    $(this).find('i.fa-chevron-up').toggleClass('fa-chevron-down');
                }
            );

            // widget focus
            $('.widget .btn-focus').clickToggle(
                    function (e) {
                        e.preventDefault();
                        $(this).find('i.fa-eye').toggleClass('fa-eye-slash');
                        $(this).parents('.widget').find('.btn-remove').addClass('link-disabled');
                        $(this).parents('.widget').addClass('widget-focus-enabled');
                        $('<div id="focus-overlay"></div>').hide().appendTo('body').fadeIn(300);

                    },
                    function (e) {
                        e.preventDefault();
                        $theWidget = $(this).parents('.widget');

                        $(this).find('i.fa-eye').toggleClass('fa-eye-slash');
                        $theWidget.find('.btn-remove').removeClass('link-disabled');
                        $('body').find('#focus-overlay').fadeOut(function () {
                            $(this).remove();
                            $theWidget.removeClass('widget-focus-enabled');
                        });
                    }
            );
            /*********************************/
            
            if (_this.mailId){
                _this.loadDisposisi();
            }
        },
        loadDisposisi: function (){
            var _this = this;
            var $table = $('#myDataTable tbody');
            $table.empty();
            
            $.ajax({
                url: '<?php echo get_action_url('service/disposisi/history'); ?>/'+_this.mailId,
                data: {type: _this.mailType}
            }).then(function(data){
                if (data.item_count > 0){
                    for (var i in data.items){
                        var disposisi = data.items[i];
                        var s = '<tr>';
                        s+= '<td>'+disposisi.pengirim+'</td>';
                        s+= '<td>'+disposisi.waktu_kirim+'</td>';
                        s+= '<td>'+disposisi.keterangan+'</td>';
                        s+= '<td>'+disposisi.penerima+'</td>';
                        s+= '<td>'+(disposisi.waktu_terima ? disposisi.waktu_terima : '-')+'</td>';
                        s+= '<td class="text-center">'+disposisi.status+'</td>';
                        s+= '</tr>';
                        
                        $table.append(s);
                    }
                }else{
                    $table.append('<tr><td colspan="6">Data disposisi tidak ada</td></tr>');
                }
            });
        }
    };
    $(document).ready(function(){
        Disposisi.init();
    });
</script>
