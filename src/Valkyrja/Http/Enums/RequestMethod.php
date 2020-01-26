<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Enums;

use Valkyrja\Enum\Enum;

/**
 * Method Definitions.
 * The set of common methods for HTTP/1.1 is defined below. Although this
 * set can be expanded, additional methods cannot be assumed to share the
 * same semantics for separately extended clients and servers.
 * The Host request-header field (section 14.23) MUST accompany all
 * HTTP/1.1 requests.
 *
 * @author Melech Mizrachi
 *
 * @link   https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html
 */
final class RequestMethod extends Enum
{
    /**
     * OPTIONS Method.
     * The OPTIONS method represents a request for information about the
     * communication options available on the request/response chain
     * identified by the Request-URI. This method allows the client to
     * determine the options and/or requirements associated with a
     * resource, or the capabilities of a server, without implying a
     * resource action or initiating a resource retrieval.
     * Responses to this method are not cacheable.
     * If the OPTIONS request includes an entity-body (as indicated by
     * the presence of Content-Length or Transfer-Encoding), then the
     * media type MUST be indicated by a Content-Type field. Although
     * this specification does not define any use for such a body,
     * future extensions to HTTP might use the OPTIONS body to make
     * more detailed queries on the server. A server that does not
     * support such an extension MAY discard the request body.
     * If the Request-URI is an asterisk ("*"), the OPTIONS request
     * is intended to apply to the server in general rather than to
     * a specific resource. Since a server's communication options
     * typically depend on the resource, the "*" request is only
     * useful as a "ping" or "no-op" type of method; it does nothing
     * beyond allowing the client to test the capabilities of the
     * server. For example, this can be used to test a proxy for
     * HTTP/1.1 compliance (or lack thereof).
     * If the Request-URI is not an asterisk, the OPTIONS request
     * applies only to the options that are available when
     * communicating with that resource.
     * A 200 response SHOULD include any header fields that indicate
     * optional features implemented by the server and applicable
     * to that resource (e.g., Allow), possibly including extensions
     * not defined by this specification. The response body, if any,
     * SHOULD also include information about the communication options.
     * The format for such a body is not defined by this specification,
     * but might be defined by future extensions to HTTP. Content negotiation
     * MAY be used to select the appropriate response format. If no response
     * body is included, the response MUST include a Content-Length field
     * with a field-value of "0".
     * The Max-Forwards request-header field MAY be used to target a specific
     * proxy in the request chain. When a proxy receives an OPTIONS request
     * on an absoluteURI for which request forwarding is permitted, the
     * proxy MUST check for a Max-Forwards field. If the Max-Forwards
     * field-value is zero ("0"), the proxy MUST NOT forward the message;
     * instead, the proxy SHOULD respond with its own communication options.
     * If the Max-Forwards field-value is an integer greater than zero,
     * the proxy MUST decrement the field-value when it forwards the
     * request. If no Max-Forwards field is present in the request, then
     * the forwarded request MUST NOT include a Max-Forwards field.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.2
     */
    public const OPTIONS = 'OPTIONS';

    /**
     * GET Method.
     * The GET method means retrieve whatever information (in the form of an
     * entity) is identified by the Request-URI. If the Request-URI refers
     * to a data-producing process, it is the produced data which shall be
     * returned as the entity in the response and not the source text of the
     * process, unless that text happens to be the output of the process.
     * The semantics of the GET method change to a "conditional GET" if the
     * request message includes an If-Modified-Since, If-Unmodified-Since,
     * If-Match, If-None-Match, or If-Range header field. A conditional GET
     * method requests that the entity be transferred only under the
     * circumstances described by the conditional header field(s). The
     * conditional GET method is intended to reduce unnecessary network
     * usage by allowing cached entities to be refreshed without requiring
     * multiple requests or transferring data already held by the client.
     * The semantics of the GET method change to a "partial GET" if the
     * request message includes a Range header field. A partial GET requests
     * that only part of the entity be transferred, as described in section
     * 14.35. The partial GET method is intended to reduce unnecessary network
     * usage by allowing partially-retrieved entities to be completed without
     * transferring data already held by the client.
     * The response to a GET request is cacheable if and only if it meets the
     * requirements for HTTP caching described in section 13.
     * See section 15.1.3 for security considerations when used for forms.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.3
     */
    public const GET = 'GET';

    /**
     * HEAD Method.
     * The HEAD method is identical to GET except that the server MUST NOT
     * return a message-body in the response. The meta information contained
     * in the HTTP headers in response to a HEAD request SHOULD be identical
     * to the information sent in response to a GET request. This method can
     * be used for obtaining meta information about the entity implied by the
     * request without transferring the entity-body itself. This method is
     * often used for testing hypertext links for validity, accessibility,
     * and recent modification.
     * The response to a HEAD request MAY be cacheable in the sense that the
     * information contained in the response MAY be used to update a previously
     * cached entity from that resource. If the new field values indicate that
     * the cached entity differs from the current entity (as would be indicated
     * by a change in Content-Length, Content-MD5, ETag or Last-Modified), then
     * the cache MUST treat the cache entry as stale.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.4
     */
    public const HEAD = 'HEAD';

    /**
     * POST Method.
     * The POST method is used to request that the origin server accept the
     * entity enclosed in the request as a new subordinate of the resource
     * identified by the Request-URI in the Request-Line. POST is designed
     * to allow a uniform method to cover the following functions:
     *      - Annotation of existing resources;
     *      - Posting a message to a bulletin board, newsgroup, mailing list,
     *        or similar group of articles;
     *      - Providing a block of data, such as the result of submitting a
     *        form, to a data-handling process;
     *      - Extending a database through an append operation.
     * The actual function performed by the POST method is determined by the
     * server and is usually dependent on the Request-URI. The posted entity
     * is subordinate to that URI in the same way that a file is subordinate
     * to a directory containing it, a news article is subordinate to a
     * newsgroup to which it is posted, or a record is subordinate to a
     * database.
     * The action performed by the POST method might not result in a resource
     * that can be identified by a URI. In this case, either 200 (OK) or 204
     * (No Content) is the appropriate response status, depending on whether
     * or not the response includes an entity that describes the result.
     * If a resource has been created on the origin server, the response SHOULD
     * be 201 (Created) and contain an entity which describes the status of the
     * request and refers to the new resource, and a Location header
     * (see section 14.30).
     * Responses to this method are not cacheable, unless the response includes
     * appropriate Cache-Control or Expires header fields. However, the 303
     * (See Other) response can be used to direct the user agent to retrieve
     * a cacheable resource.
     * POST requests MUST obey the message transmission requirements set
     * out in section 8.2.
     * See section 15.1.3 for security considerations.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.5
     */
    public const POST = 'POST';

    /**
     * PUT Method.
     * The PUT method requests that the enclosed entity be stored under the
     * supplied Request-URI. If the Request-URI refers to an already existing
     * resource, the enclosed entity SHOULD be considered as a modified version
     * of the one residing on the origin server. If the Request-URI does not
     * point to an existing resource, and that URI is capable of being defined
     * as a new resource by the requesting user agent, the origin server can
     * create the resource with that URI. If a new resource is created, the
     * origin server MUST inform the user agent via the 201
     * (Created) response. If an existing resource is modified, either the 200
     * (OK) or 204 (No Content) response codes SHOULD be sent to indicate
     * successful completion of the request. If the resource could not be
     * created or modified with the Request-URI, an appropriate error response
     * SHOULD be given that reflects the nature of the problem. The recipient
     * of the entity MUST NOT ignore any Content-* (e.g. Content-Range) headers
     * that it does not understand or implement and MUST return a 501 (Not
     * Implemented) response in such cases.
     * If the request passes through a cache and the Request-URI identifies one
     * or more currently cached entities, those entries SHOULD be treated as
     * stale. Responses to this method are not cacheable.
     * The fundamental difference between the POST and PUT requests is
     * reflected in the different meaning of the Request-URI. The URI in a POST
     * request identifies the resource that will handle the enclosed entity.
     * That resource might be a data-accepting process, a gateway to some other
     * protocol, or a separate entity that accepts annotations. In contrast,
     * the URI in a PUT request identifies the entity enclosed with the request
     * -- the user agent knows what URI is intended and the server MUST NOT
     * attempt to apply the request to some other resource. If the server
     * desires that the request be applied to a different URI,
     * it MUST send a 301 (Moved Permanently) response; the user agent MAY then
     * make its own decision regarding whether or not to redirect the request.
     * A single resource MAY be identified by many different URIs. For example,
     * an article might have a URI for identifying "the current version" which
     * is separate from the URI identifying each particular version. In this
     * case, a PUT request on a general URI might result in several other URIs
     * being defined by the origin server.
     * HTTP/1.1 does not define how a PUT method affects the state of an origin
     * server.
     * PUT requests MUST obey the message transmission requirements set out in
     * section 8.2.
     * Unless otherwise specified for a particular entity-header, the
     * entity-headers in the PUT request SHOULD be applied to the resource
     * created or modified by the PUT.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.6
     */
    public const PUT = 'PUT';

    /**
     * DELETE Method.
     * The DELETE method requests that the origin server delete the resource
     * identified by the Request-URI. This method MAY be overridden by human
     * intervention
     * (or other means) on the origin server. The client cannot be guaranteed
     * that the operation has been carried out, even if the status code
     * returned from the origin server indicates that the action has been
     * completed successfully. However, the server SHOULD NOT indicate success
     * unless, at the time the response is given, it intends to delete the
     * resource or move it to an inaccessible location.
     * A successful response SHOULD be 200 (OK) if the response includes an
     * entity describing the status, 202 (Accepted) if the action has not yet
     * been enacted, or 204 (No Content) if the action has been enacted but the
     * response does not include an entity.
     * If the request passes through a cache and the Request-URI identifies one
     * or more currently cached entities, those entries SHOULD be treated as
     * stale. Responses to this method are not cacheable.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.7
     */
    public const DELETE = 'DELETE';

    /**
     * TRACE Method.
     * The TRACE method is used to invoke a remote, application-layer loop-
     * back of the request message. The final recipient of the request SHOULD
     * reflect the message received back to the client as the entity-body of a
     * 200 (OK) response. The final recipient is either the
     * origin server or the first proxy or gateway to receive a Max-Forwards
     * value of zero (0) in the request (see section 14.31). A TRACE request
     * MUST NOT include an entity.
     * TRACE allows the client to see what is being received at the other end
     * of the request chain and use that data for testing or diagnostic
     * information. The value of the Via header field (section 14.45) is of
     * particular interest, since it acts as a trace of the request chain. Use
     * of the Max-Forwards header field allows the client to limit the length
     * of the request chain, which is useful for testing a chain of proxies
     * forwarding messages in an infinite loop.
     * If the request is valid, the response SHOULD contain the entire request
     * message in the entity-body, with a Content-Type of "message/http".
     * Responses to this method MUST NOT be cached.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.8
     */
    public const TRACE = 'TRACE';

    /**
     * CONNECT Method.
     * This specification reserves the method name CONNECT for use with a proxy
     * that can dynamically switch to being a tunnel (e.g. SSL tunneling [44]).
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.9
     */
    public const CONNECT = 'CONNECT';

    /**
     * PATCH Method.
     * As defined in RFC-5789.
     * The PATCH method requests that a set of changes described in the
     * request entity be applied to the resource identified by the Request-
     * URI. The set of changes is represented in a format called a "patch
     * document" identified by a media type. If the Request-URI does not
     * point to an existing resource, the server MAY create a new resource,
     * depending on the patch document type (whether it can logically modify
     * a null resource) and permissions, etc.
     *
     * @link https://tools.ietf.org/html/rfc5789
     */
    public const PATCH = 'PATCH';

    /**
     * Valid request method values to avoid reflection class being created
     * dynamically in the Enum::isValid() method.
     *
     * @constant array
     */
    protected const VALUES = [
        self::GET     => self::GET,
        self::POST    => self::POST,
        self::PUT     => self::PUT,
        self::PATCH   => self::PATCH,
        self::DELETE  => self::DELETE,
        self::HEAD    => self::HEAD,
        self::OPTIONS => self::OPTIONS,
        self::TRACE   => self::TRACE,
        self::CONNECT => self::CONNECT,
    ];
}
