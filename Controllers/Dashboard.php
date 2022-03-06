<?php

namespace HexMakina\komer\Controllers;

use HexMakina\kadro\Auth\Operator;
use HexMakina\Tempus\Dato;

class Dashboard extends \HexMakina\kadro\Controllers\Kadro
{
    // backend controller, requires login
    public function requiresOperator(): bool
    {
      return true;
    }

    public function home()
    {
        return 'home/home';
    }

    public function dash()
    {
        return 'dashboard/home';
    }


    public function bootstrap()
    {
        $target_controller = $this->get('HexMakina\BlackBox\RouterInterface')->targetController();
        $target_controller = $this->get('Controllers\\' . $target_controller);

        if (!$smith->hasFilter('date_start')) {
            $smith->filters('date_start', Dato::format($target_controller->get('settings.app.time_window_start'), Dato::FORMAT));
        }

        if (!$smith->hasFilter('date_stop')) {
            $smith->filters('date_stop', Dato::format($target_controller->get('settings.app.time_window_stop'), Dato::FORMAT));
        }
        
        $target_controller->execute($this->get('HexMakina\BlackBox\RouterInterface')->targetMethod());
    }

    public function common_viewport($target_controller)
    {
        $target_controller->viewport('controller', $target_controller);
        if(method_exists($target_controller, 'modelClassName'))
            $target_controller->viewport('active_section', $target_controller->modelClassName()::model_type());

        // $all_operators = Operator::filter();
        // $target_controller->viewport('all_operators', $all_operators);
        // $target_controller->viewport('services', $target_controller->get('Models\Service::class')::filter());
        // $target_controller->viewport('CurrentOperator', $this->get('HexMakina\BlackBox\Auth\OperatorInterface'));
    }

    public function change_time_window(){
      $period = $this->make_period($this->router()->params('period'));
      // dd(Dato::format($period->getStartDate(), Dato::FORMAT), Dato::format($period->getEndDate(), Dato::FORMAT));
      $smith = $this->get('HexMakina\BlackBox\StateAgentInterface');
      $smith->filters('date_start', $period->getStartDate()->format(Dato::FORMAT));
      $smith->filters('date_stop', $period->getEndDate()->format(Dato::FORMAT));
      $this->router()->hopBack();
    }

    private function make_period($period){

      $end_of_time = new \DateTimeImmutable(Dato::today());

      if($period === 'today')
        $starts_on = $end_of_time;
      else{
        $period = ucfirst($period[0]);
        $interval = new \DateInterval('P1'.$period);
        $starts_on = $end_of_time->sub($interval);
      }

      if($period === 'D')
        $end_of_time = $starts_on->setTime(23,59,59); // yesterday
      else{
        $end_of_time = $end_of_time->setTime(23,59,59); // any other period
      }

      $ret = new \DatePeriod($starts_on, new \DateInterval('P1D'), $end_of_time);
      return $ret;
    }

}
