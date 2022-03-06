<?php

namespace HexMakina\komer\Controllers;

use HexMakina\Tempus\Dato;

class Home extends \HexMakina\kadro\Controllers\Kadro
{
    // frontend controller, doesn't require login
    public function requiresOperator(): bool
    {
      return false;
    }

    public function home()
    {
        return 'home/home';
    }

    public function bootstrap()
    {
        $target_controller = $this->get('HexMakina\BlackBox\RouterInterface')->targetController();
        $target_controller = $this->get('Controllers\\' . $target_controller);

        $smith = $target_controller->get('HexMakina\BlackBox\StateAgentInterface');


        $this->common_viewport($target_controller);
        $target_controller->execute($this->get('HexMakina\BlackBox\RouterInterface')->targetMethod());
    }

    public function common_viewport($target_controller)
    {
        // $all_operators = Operator::filter();
        // $target_controller->viewport('all_operators', $all_operators);
        // $target_controller->viewport('services', $target_controller->get('Models\Service::class')::filter());
        // $target_controller->viewport('CurrentOperator', $this->get('HexMakina\BlackBox\Auth\OperatorInterface'));
    }
}
