<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Component;

/**
 * @method static string EMIT_NO_THROW()
 * @method static string EMIT_NO_EMITTER_FOR_TYPE()
 * @method static string EMIT_NO_PRIMARY_KEY()
 * @method static string EXPLORE_NO_THROW()
 * @method static string EXPLORE_NO_EXPLORER_FOR_TYPE()
 * @method static string WEB_HTTP_HANDLE_NO_THROW()
 * @method static string WEB_HTTP_HANDLE_NO_HANDLER_FOR_PATH()
 * @method static string WEB_HTTP_HANDLE_DISABLED()
 * @method static string RECEIVE_NO_THROW()
 * @method static string RECEIVE_NO_RECEIVER_FOR_TYPE()
 * @method static string RECEIVE_NO_SAVE_MAPPINGS_NOT_PROCESSED()
 * @method static string STATUS_REPORT_NO_THROW()
 * @method static string STATUS_REPORT_NO_STATUS_REPORTER_FOR_TYPE()
 * @method static string PORTAL_LOAD_ERROR()
 * @method static string PORTAL_EXTENSION_LOAD_ERROR()
 * @method static string PORTAL_NODE_CONFIGURATION_INVALID()
 * @method static string MARK_AS_FAILED_ENTITY_IS_UNMAPPED()
 * @method static string STORAGE_STREAM_NORMALIZER_CONVERTS_HINT_TO_FILENAME()
 */
abstract class LogMessage
{
    public static function __callStatic(string $name, array $arguments): string
    {
        return $name;
    }
}
