<?php
namespace Utils;

/**
 * 指定された処理を実行し、結果をEitherでラップして返す。
 * 成功した場合はRight(結果)を、例外が発生した場合はLeft(例外)を返す。
 *
 * @param callable $callable 実行する処理
 * @return Either
 */
function attempt(callable $callable)
{
    try {
        return Either::right($callable());
    } catch (\Throwable $e) {
        return Either::left($e);
    }
}