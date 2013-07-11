<?php //use_helper('Object') ?>


<div id="tv_admin_container">

<p>
Escriba el nombre de la persona que desea a&ntilde;adir. En caso de que ya exista en la base de datos aparecer&aacute; en una lista deplegable, donde usted puede selecionarla y <strong>usarla</strong>. Si no existe en la base de datos <strong>cree</strong> una entrada nueva con el nombre escrito.
</p>

<fieldset style="width: 350px">
  
  <div class="form-row">
    <?php echo label_for('name', 'Nombre:', 'class="required" ') ?>
    <div class="content">
      <input name="name" id="name" placeholder="nombre a buscar" style="width: 200px;"/>
      <input id="name-id" type="hidden"/>
      <p id="name-info"></p>
      <span id="indicator1" style="display: none"><?php echo image_tag('admin/load/spinner.gif', 'size=18x18 alt=trabajando...') ?></span>
    </div>
  </div>
  
</fieldset>

<ul class="tv_admin_actions">
  <li>
    <input id="opener-inside-<?php echo $role_id?>" class="tv_admin_action_create MB_focusable" type="button" value="Nuevo">
  </li>
  <li>
    <input id="tv_admin_action_create" class="tv_admin_action_create" type="button" value="Usar" />
  </li>
</ul>

</div>
 
<div style="clear:right"></div>

<div id="dialog-modal-new-inside-<?php echo $role_id?>" title="Editar Nueva Novedad" data-ajax="true" data-url="/neweditar.php/serials/createrelation<?php echo $template?>/mm/<?php echo $mm_id?>/role/<?php echo $role_id?>"></div>
<script type="text/javascript">
//<![CDATA[
$("#dialog-modal-new-inside-<?php echo $role_id?>").dialog({
   dialogClass: 'dialogNewInside',
   hide: "highlight",
   autoOpen: false,
   modal: true,
   minWidth: 800,
   position: [700, 250],
   buttons: {
      'Cancel': function() {
              $(this).dialog('close');
       }
   },
   open: function() {
     self = $(this);
     if (self.data("ajax")) {
         if ($('#name').val().length == 0){
             self.load(self.data("url"));
         } else {
             var url = self.data("url") + '/name/'+$('#name-id').text()+'-'+$('#name').val();
             self.load(encodeURI(url));
         }
     }
   }
});
$( "#opener-inside-<?php echo $role_id?>" ).click(function() {
   $( "#dialog-modal-new-inside-<?php echo $role_id?>" ).dialog( "open" );
   return false;
});

$("#tv_admin_action_create").click(function() {
   if($('#name').val().length!=0) {
       $.ajax({
           method: 'POST',
           url: '<?php echo url_for('serials/link' . $template)?>',
           data:{preview: 'true', mm: '<?php echo $mm_id?>', role:'<?php echo $role_id?>', person: parseInt($('#name-id').text())}, 
           success: function(e) {
               $('#<?php echo $role_id?>_person_mms').html(e);
           }
       });
       $('#dialog-modal-new-<?php echo $role_id?>').dialog('close');
   }else{
      alert('Selecione antes una persona');
   }
   return false;
});

if($('MB_content')) $('MB_content').attr({ 'position' : 'static' });
$(function() {
        $( "#name" ).autocomplete({
            source: '<?php echo url_for('serials/autoComplete')?>',
            minLength: 2,
            preventDuplicates: true,
            onReady: function(){
               $('#name').focus();
            },
            focus: function( event, ui ) {
                $( "#name" ).val( ui.item.name );
                return false;
            },
            select: function( event, ui ) {
               $( "#name" ).val( ui.item.name );
               $( "#name-id" ).html( ui.item.id );
               $( "#name-info" ).html( ui.item.info );
               return false;
            }
        })
        .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
          return $( "<li>" )
          .append( "<a>" + item.id + " - " + item.name + "</a>" )
          .appendTo( ul );
        };
    });
//]]>

</script>