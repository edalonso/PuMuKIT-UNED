<div class="mm_player" style="">
<div style="float: left; width: 620px">
     <?php if (isset($player)): ?>
       <?php include_partial('player'.$player, array('file' => $file, 'w' => 620, 'h' => 465))?>
     <?php else : ?>
       <?php include_partial('player', array('file' => $file, 'w' => 620, 'h' => 465))?>
     <?php endif ?>

  <div style="margin: 490px 0px 0px 0px;">
    <div class="num_view">
       <div style="float:left">
         <?php echo __("Idioma del video")?>: <span class="num_view_number"><?php echo $file->getLanguage() ?></span>
       </div>
       <div style="float: right; font-weight: normal;">
         <?php echo $mmobj->getRecordDate('d/m/Y') ?>
       </div>
     <?php //echo __('Visto')?>
       <span class="num_view_number"><?php //echo $file->getNumView()?></span>
     <?php //echo (($file->getNumView() == 1)?__('vez'):__('veces'))?>
    </div>


    <div class="title">
     <?php echo $mmobj->getTitle() ?>
     <?php echo $mmobj->getSubtitle() ?>
    </div>


    <p class="description">
     <?php echo nl2br($mmobj->getDescription()) ?>
    </p>
     <?php include_partial('mmobj/bodyMm', array('mm' => $mmobj, 'roles' => $roles)) ?>
  </div>
</div>
  <div class="grid_5_mmobj">
   <!-- FIXME. show only public multimedia objects -->
     <?php include_partial('mmobj/other', array('texto' => __('Videos de la misma serie'),
'mmobjs' => $mms))?>

     <?php $c = new Criteria()?>
     <?php $c->addAscendingOrderByColumn('rand()');?>
     <?php $c->setLimit(8)?>
     <?php $c->addDescendingOrderByColumn(MmPeer::ID)?>
     <?php include_partial('mmobj/other', array('texto' => __('Relacionados'), 'mmobjs' => MmPeer::doSelect($c)))?>
     <?php //include_partial('mmobj/other', array('texto' => __('Relacionados'), 'mmobjs' => $mmobj->getSimilarMms()))?>
     <?php include_partial('mmobj/share', array('mmobj' => $c, 'file' => $file))?>
  </div>
</div>
