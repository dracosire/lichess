<?php $view->extend('LichessBundle::layout') ?>

<div class="lichess_game lichess_game_not_started clearfix lichess_player_<?php echo $player->getColor() ?>">
    <div class="lichess_board_wrap">
        <?php $view->output('LichessBundle:Main:staticBoard', array('color' => $player->getColor())) ?>
        <div class="lichess_overboard wait_anybody">
            <img src="/bundle/lichess/images/hloader.gif" width="220" height="33" /><br />
            Searching for an opponent
        </div>
    </div> 
    <div class="lichess_ground">
        <div class="lichess_table lichess_table_not_started">
            <a href="<?php echo $view->router->generate('lichess_invite_friend', array('color' => $player->getColor())) ?>" class="lichess_button" title="Invite a friend to play with you">Play with a friend</a>
            <a href="<?php echo $view->router->generate('lichess_invite_ai', array('color' => $player->getColor())) ?>" class="lichess_button" title="Challenge the artificial intelligence">Play with the machine</a>
            <span class="lichess_button active">Play with anybody</span>
        </div>
    </div>
</div>

<?php $view->output('LichessBundle:Game:data', array('player' => $player, 'possibleMoves' => null, 'parameters' => $parameters, 'isOpponentConnected' => false)) ?>
