<?php $game = $player->getGame() ?>
<?php $winner = $game->getWinner() ?>
<?php $opponent = $player->getOpponent() ?>
<div class="lichess_table finished <?php echo $game->getNext() ? ' lichess_table_next' : '' ?>">
    <div class="lichess_opponent">
        <?php if ($opponent->getIsAi()): ?>
            <span>Opponent is Crafty A.I. level <?php echo $opponent->getAiLevel() ?></span>
        <?php else: ?>
            <div class="opponent_status">
              <?php $view->actions->output('LichessBundle:Player:opponent', array('path' => array('hash' => $game->getHash(), 'color' => $player->getColor(), 'playerFullHash' => $player->getFullHash()))) ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="lichess_separator"></div>
    <div class="lichess_current_player">
        <?php if($winner): ?>
            <div class="lichess_player <?php echo $winner->getColor() ?>">
                <div class="lichess_piece king <?php echo $winner->getColor() ?>"></div>
                <p><?php echo $game->getStatusMessage() ?><br /><?php echo ucfirst($winner->getColor()) ?> is victorious</p>
            </div>
        <?php else: ?>
            <div class="lichess_player">
                <p>Stalemate.</p>
            </div>
        <?php endif; ?>
    </div>
    <div class="lichess_control clearfix">
        <label title="Toggle the chat" class="lichess_enable_chat"><input type="checkbox" checked="checked" />Chat</label>
        <a class="lichess_new_game" title="Back to homepage" href="<?php echo $view->router->generate('lichess_homepage') ?>">New game</a>
        <?php if(!$opponent->getIsAi()): ?>
            <?php if(isset($nextGame)): ?>
                    <div class="lichess_separator"></div>
                <?php if($player->getColor() == $nextGame->getCreator()->getColor()): ?>
                    <div class="lichess_play_again_join">
                        Your opponent wants to play a new game with you.&nbsp;
                        <a class="lichess_play_again" title="Play with the same opponent again" href="<?php echo $view->router->generate('lichess_rematch', array('hash' => $player->getFullHash())) ?>">Join the game</a>
                    </div>
                <?php else: ?>
                    <div class="lichess_play_again_join">
                        Rematch proposal sent.<br />
                        Waiting for your opponent...
                    </div>
                <?php endif; ?>
            <?php else: ?>
                or <a class="lichess_rematch" title="Play with the same opponent again" href="<?php echo $view->router->generate('lichess_rematch', array('hash' => $player->getFullHash())) ?>">Rematch</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
