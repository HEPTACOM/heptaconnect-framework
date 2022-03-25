<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\File;

use Heptacom\HeptaConnect\Dataset\Base\File\FileReferenceContract;
use Psr\Http\Message\RequestInterface;

abstract class FileReferenceFactoryContract
{
    /**
     * Creates a file reference from a public URI. This public URI **MUST** comply with the following rules:
     *
     * - A `GET` request without any additional header lines **MUST** respond with the contents of the referenced file.
     * - The hostname of the URI **MUST** be resolvable by public DNS servers and **MUST** have either an `A` record or
     *   an `AAAA` record stored in its public DNS.
     * - Either the `A` record or the `AAAA` record of the publicly resolved DNS of the URI **MUST** point to a host
     *   that is reachable from a third party and is able to fulfil a `GET` request.
     *
     * Usage of this method creates a dependency on the availability of the referenced hosts from a third party host.
     *
     * Any package using this method **SHOULD** include a list of all hostnames that are used with this method as
     * dependencies in its documentation.
     */
    abstract public function fromPublicUrl(string $publicUrl): FileReferenceContract;

    /**
     * Creates a file reference from a PSR-7 request object.
     *
     * When the reference is resolved, the request is performed from within the context of the source portal node, using
     * the service `Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpClientContract` from the portal's
     * dependency-injection-container. A portal using this method **MAY** modify how the request is performed by
     * decorating this service.
     *
     * Usage of this method creates a dependency on the availability of the referenced hosts from the HEPTAconnect
     * application server. Resolving the returned file reference will send the given request from the HEPTAconnect
     * application server and the response will be used as the referenced file's contents.
     *
     * Any package using this method **SHOULD** include a list of all hostnames that are used with this method as
     * dependencies in its documentation.
     */
    abstract public function fromRequest(RequestInterface $request): FileReferenceContract;

    /**
     * Creates a file reference from static contents.
     *
     * When this method is called, the given file contents are saved to storage. When the returned file reference is
     * resolved, the file contents will be read from storage. The given MIME type will be used as `Content-Type` header.
     *
     * As the file contents are stored by the HEPTAconnect storage layer, it **MAY** become relevant to housekeeping.
     * At the time this method is called, the returned file reference **MUST** be able to be resolved to the contents
     * that were provided in this method. At any later time it **SHOULD NOT** be trusted that the file reference can be
     * resolved, as arbitrary conditions could trigger housekeeping to remove the file contents from storage.
     */
    abstract public function fromContents(
        string $contents,
        string $mimeType = 'application/octet-stream'
    ): FileReferenceContract;
}
