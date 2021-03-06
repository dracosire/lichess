<?php $turnPlayer = $player->getGame()->getTurnPlayer() ?>
<?php $opponent = $player->getOpponent() ?>
<div class="lichess_table <?php $player->getGame()->getIsFinished() && print 'finished ' ?>spectator">
    <div class="lichess_opponent">
        <?php $view->actions->output('LichessBundle:Player:opponent', array('path' => array('hash' => $player->getGame()->getHash(), 'color' => $player->getColor(), 'playerFullHash' => ''))) ?>
    </div>
    <div class="lichess_separator"></div>
    <div class="lichess_current_player">
        <?php if($player->getGame()->getIsFinished()): ?>
            <?php if($winner = $player->getGame()->getWinner()): ?>
                <div class="lichess_player <?php echo $winner->getColor() ?>">
                    <div class="lichess_piece king <?php echo $winner->getColor() ?>"></div>
                    <p><?php echo $player->getGame()->getStatusMessage() ?><br /><?php echo ucfirst($winner->getColor()) ?> is victorious</p>
                </div>
            <?php else: ?>
                <div class="lichess_player">
                    <p>Stalemate.</p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="lichess_player white <?php echo $turnPlayer->isBlack() ? ' none' : '' ?>">
                <div class="lichess_piece king white"></div>
                <p>White plays</p>
            </div>
            <div class="lichess_player black <?php echo $turnPlayer->isWhite() ? ' none' : '' ?>">
                <div class="lichess_piece king black"></div>
                <p>Black plays</p>
            </div>
         <?php endif; ?>
    </div>
    <?php if(!$player->getGame()->getIsFinished()): ?>
        <div class="lichess_control clearfix">
            <label class="lichess_enable_animation"><input type="checkbox" checked="checked" />Animation</label>
        </div>
    <?php endif; ?>
    <div class="lichess_separator"></div>
        <?php $view->actions->output('LichessBundle:Player:opponent', array('path' => array('hash' => $player->getGame()->getHash(), 'color' => $player->getOpponent()->getColor(), 'playerFullHash' => ''))) ?>
</div>
