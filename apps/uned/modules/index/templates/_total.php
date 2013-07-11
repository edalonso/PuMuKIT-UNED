<div style="padding: 2px 5px" class="menu_left">
  <?php echo SerialPeer::doCountPublic(true) ?> <?php echo __('Series') ?> <br />
  <?php echo MmPeer::doCountVideoPublic(true) ?> <?php echo __('Vídeos') ?> <br />
  <?php echo MmPeer::doCountAudioPublic(true) ?> <?php echo __('Audios') ?> <br />
  <?php printf("%.2f", FilePeer::doCountDurationPublic()) ?> <?php echo __('Horas') ?> <br />
</div>
