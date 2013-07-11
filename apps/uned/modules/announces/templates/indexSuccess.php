<h1 id="announces_h1"><?php echo __('Mediateca por meses')?></h1>

<p style="padding:10px 0px">
  <?php echo __('Últimas grabaciones realizadas ordenadas según la fecha de publicación.')?>
</p>

<div id="announce">
  <?php include_partial('announce', array('announces' => $announces, 'date' => $date)) ?>
</div>
<div id="cargando" style="display: none"><img src="/images/admin/load/spinner.gif"/></div>

<script type="text/javascript">
var element = $('announce');
var cargando = $('cargando');

var AnnounceDate = Class.create();
AnnounceDate.prototype = {
  // NOTE: JS months are [0, 11]
  initialize: function(year, month) {
    this.year = year;
    this.month = month;
  },
  decMonth: function() {
    this.month -= 1;
    if (this.month == -1){
      this.month = 11;
      this.year -= 1;
    }
  },
  toStringParameter: function() {
    return this.year + "/" + ( this.month+ 1)
  }
};
<?php $aux_date = strtotime($date);?>
var anDate = new AnnounceDate(<?php echo date('Y', $aux_date);?>, <?php echo date('m', strtotime('-1 month', $aux_date));?>);
anDate.decMonth();

Event.observe(window, 'scroll', function(){
    //TODO Chrome document.body.scrollTop - Firefox document.documentElement.scrollTop
    var scrollBottom = document.documentElement.getHeight() - document.documentElement.scrollTop - document.body.scrollTop - document.viewport.getHeight();
    //console.log(document.documentElement.getHeight(), document.body.scrollTop, document.viewport.getHeight())
    //console.log(scrollBottom);

    if (scrollBottom == 0){
      cargando.show();
      anDate = new AnnounceDate(parseInt($$('.categories_title_hidden')[$$('.categories_title_hidden').length-1].value.split('-')[1]), 
				parseInt($$('.categories_title_hidden')[$$('.categories_title_hidden').length-1].value.split('-')[0]) - 1);
      anDate.decMonth();
      new Ajax.Request('announces/part',{
	method: 'get',
	parameters: {fecha: anDate.toStringParameter(), otro: 99},
	onSuccess: function(respuesta){
	  cargando.hide();
          var returnDate = respuesta.getResponseHeader('returndate');
          if (returnDate != $$('.categories_title_hidden')[$$('.categories_title_hidden').length-1].value) {
              element.insert(respuesta.responseText);
          }
        }
      });
    }
  });
</script>
