<?php
namespace Ironopolis\Skeleton\Exceptions;
 
use Exception;
 
class PageNotFoundException extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function render($request)
    {
      return response()->view(
        'stellify::errors.404',
        array(
            'exception' => $this
        )
      );
    }
}