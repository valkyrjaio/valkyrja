<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage\Enums;

use Valkyrja\Enum\Enum;

/**
 * Header Field Definitions.
 * This section defines the syntax and semantics of all standard HTTP/1.1
 * header fields. For entity-header fields, both sender and recipient refer
 * to either the client or the server, depending on who sends and who
 * receives the entity.
 *
 * @author Melech Mizrachi
 *
 * @link   https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html
 */
final class Header extends Enum
{
    /**
     * Accept Header.
     * The Accept request-header field can be used to specify certain
     * media types which are acceptable for the response. Accept
     * headers can be used to indicate that the request is
     * specifically limited to a small set of desired
     * types, as in the case of a request for an
     * in-line image.
     *      Accept           = "Accept" ":"
     *                         #( media-range [ accept-params ] )
     *      media-range      = ( "*\/*"
     *                         | ( type "/" "*" )
     *                         | ( type "/" subtype )
     *                         ) *( ";" parameter )
     *      accept-params    = ";" "q" "=" qvalue *( accept-extension )
     *      accept-extension = ";" token [ "=" ( token | quoted-string ) ].
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.1
     */
    public const ACCEPT = 'Accept';

    /**
     * Accept-Charset Header.
     * The Accept-Charset request-header field can be used to indicate
     * what character sets are acceptable for the response. This field
     * allows clients capable of understanding more comprehensive or
     * special- purpose character sets to signal that capability to
     * a server which is capable of representing documents in those
     * character sets.
     *      Accept-Charset = "Accept-Charset" ":"
     *                       1#( ( charset | "*" )[ ";" "q" "=" qvalue ] ).
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.2
     */
    public const ACCEPT_CHARSET = 'Accept-Charset';

    /**
     * Accept-Encoding Header.
     * The Accept-Encoding request-header field is similar to Accept, but
     * restricts the content-codings (section 3.5) that are acceptable
     * in the response documents in those character sets.
     *      Accept-Encoding = "Accept-Encoding" ":"
     *                        1#( codings [ ";" "q" "=" qvalue ] )
     *      codings         = ( content-coding | "*" ).
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.3
     */
    public const ACCEPT_ENCODING = 'Accept-Encoding';

    /**
     * Accept-Language Header.
     * The Accept-Language request-header field is similar to Accept, but
     * restricts the set of natural languages that are preferred as a
     * response to the request. Language tags are defined in
     * section 3.10.
     *      Accept-Language = "Accept-Language" ":"
     *                        1#( language-range [ ";" "q" "=" qvalue ] )
     *      language-range  = ( ( 1*8ALPHA *( "-" 1*8ALPHA ) ) | "*" ).
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
     */
    public const ACCEPT_LANGUAGE = 'Accept-Language';

    /**
     * Accept-Ranges Header.
     * The Accept-Ranges response-header field allows the server to
     * indicate its acceptance of range requests for a resource:
     *      Accept-Ranges     = "Accept-Ranges" ":" acceptable-ranges
     *      acceptable-ranges = 1#range-unit | "none".
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.5
     */
    public const ACCEPT_RANGES = 'Accept-Ranges';

    /**
     * Age Header.
     * The Age response-header field conveys the sender's estimate of the
     * amount of time since the response (or its revalidation) was
     * generated at the origin server. A cached response is "fresh" if
     * its age does not exceed its freshness lifetime. Age values are
     * calculated as specified in section 13.2.3.
     *      Age       = "Age" ":" age-value
     *      age-value = delta-seconds.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.6
     */
    public const AGE = 'Age';

    /**
     * Allow Header.
     * The Allow entity-header field lists the set of methods supported
     * by the resource identified by the Request-URI. The purpose of this
     * field is strictly to inform the recipient of valid methods
     * associated with the resource. An Allow header field MUST be
     * present in a 405 (Method Not Allowed) response.
     *      Allow = "Allow" ":" #Method.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.7
     */
    public const ALLOW = 'Allow';

    /**
     * Authorization Header.
     * A user agent that wishes to authenticate itself with a server--
     * usually, but not necessarily, after receiving a 401 response--does
     * so by including an Authorization request-header field with the
     * request.  The Authorization field value consists of credentials
     * containing the authentication information of the user agent for
     * the realm of the resource being requested.
     *      Authorization = "Authorization" ":" credentials.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.8
     */
    public const AUTHORIZATION = 'Authorization';

    /**
     * Cache-Control Header.
     * The Cache-Control general-header field is used to specify directives
     * that MUST be obeyed by all caching mechanisms along the request/response
     * chain. The directives specify behavior intended to prevent caches
     * from adversely interfering with the request or response. These
     * directives typically override the default caching algorithms.
     * Cache directives are unidirectional in that the presence of a
     * directive in a request does not imply that the same directive
     * is to be given in the response.
     * Note that HTTP/1.0 caches might not implement Cache-Control and
     * might only implement Pragma: no-cache (see section 14.32).
     * Cache directives MUST be passed through by a proxy or gateway
     * application, regardless of their significance to that
     * application, since the directives might be applicable
     * to all recipients along the request/response chain.
     * It is not possible to specify a cache- directive
     * for a specific cache.
     *      Cache-Control   = "Cache-Control" ":" 1#cache-directive
     *      cache-directive = cache-request-directive
     *                        | cache-response-directive
     *      cache-request-directive =
     *          "no-cache"                          ; Section 14.9.1
     *          | "no-store"                          ; Section 14.9.2
     *          | "max-age" "=" delta-seconds         ; Section 14.9.3, 14.9.4
     *          | "max-stale" [ "=" delta-seconds ]   ; Section 14.9.3
     *          | "min-fresh" "=" delta-seconds       ; Section 14.9.3
     *          | "no-transform"                      ; Section 14.9.5
     *          | "only-if-cached"                    ; Section 14.9.4
     *          | cache-extension                     ; Section 14.9.6
     *      cache-response-directive =
     *          "public"                               ; Section 14.9.1
     *          | "private" [ "=" <"> 1#field-name <"> ] ; Section 14.9.1
     *          | "no-cache" [ "=" <"> 1#field-name <"> ]; Section 14.9.1
     *          | "no-store"                             ; Section 14.9.2
     *          | "no-transform"                         ; Section 14.9.5
     *          | "must-revalidate"                      ; Section 14.9.4
     *          | "proxy-revalidate"                     ; Section 14.9.4
     *          | "max-age" "=" delta-seconds            ; Section 14.9.3
     *          | "s-maxage" "=" delta-seconds           ; Section 14.9.3
     *          | cache-extension                        ; Section 14.9.6
     *      cache-extension = token [ "=" ( token | quoted-string ) ].
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.9
     */
    public const CACHE_CONTROL = 'Cache-Control';

    /**
     * Connection Header.
     * The Connection general-header field allows the sender to specify
     * options that are desired for that particular connection and
     * MUST NOT be communicated by proxies over further connections.
     * The Connection header has the following grammar:
     *      Connection       = "Connection" ":" 1#(connection-token)
     *      connection-token = token.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.10
     */
    public const CONNECTION = 'Connection';

    /**
     * Content-Encoding Header.
     * The Content-Encoding entity-header field is used as a modifier to the
     * media-type. When present, its value indicates what additional content
     * codings have been applied to the entity-body, and thus what decoding
     * mechanisms must be applied in order to obtain the media-type referenced
     * by the Content-Type header field. Content-Encoding is primarily used
     * to allow a document to be compressed without losing the identity of
     * its underlying media type.
     *      Content-Encoding = "Content-Encoding" ":" 1#content-coding.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.11
     */
    public const CONTENT_ENCODING = 'Content-Encoding';

    /**
     * Content-Language Header.
     * The Content-Language entity-header field describes the natural
     * language(s) of the intended audience for the enclosed entity.
     * Note that this might not be equivalent to all the languages used
     * within the entity-body.
     *      Content-Language = "Content-Language" ":" 1#language-tag.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.12
     */
    public const CONTENT_LANGUAGE = 'Content-Language';

    /**
     * Content-Length Header.
     * The Content-Length entity-header field indicates the size of the
     * entity-body, in decimal number of OCTETs, sent to the recipient or,
     * in the case of the HEAD method, the size of the entity-body that
     * would have been sent had the request been a GET.
     *      Content-Length = "Content-Length" ":" 1*DIGIT.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.13
     */
    public const CONTENT_LENGTH = 'Content-Length';

    /**
     * Content-Location Header.
     * The Content-Location entity-header field MAY be used to supply the
     * resource location for the entity enclosed in the message when that
     * entity is accessible from a location separate from the requested
     * resource's URI. A server SHOULD provide a Content-Location for the
     * variant corresponding to the response entity; especially in the case
     * where a resource has multiple entities associated with it, and those
     * entities actually have separate locations by which they might be
     * individually accessed, the server SHOULD provide a Content-Location for
     * the particular variant which is returned.
     *      Content-Location = "Content-Location" ":"
     *                         ( absoluteURI | relativeURI ).
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.14
     */
    public const CONTENT_LOCATION = 'Content-Location';

    /**
     * Content-MD5 Header.
     * The Content-MD5 entity-header field, as defined in RFC 1864 [23],
     * is an MD5 digest of the entity-body for the purpose of providing
     * an end-to-end message integrity check (MIC) of the entity-body.
     * (Note: a MIC is good for detecting accidental modification of the
     * entity-body in transit, but is not proof against malicious attacks.)
     *      Content-MD5 = "Content-MD5" ":" md5-digest
     *      md5-digest  = <base64 of 128 bit MD5 digest as per RFC 1864>.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.15
     */
    public const CONTENT_MD5 = 'Content-MD5';

    /**
     * Content-Range Header.
     * The Content-Range entity-header is sent with a partial entity-body
     * to specify where in the full entity-body the partial body should be
     * applied. Range units are defined in section 3.12.
     *      Content-Range           = "Content-Range" ":" content-range-spec
     *      content-range-spec      = byte-content-range-spec
     *      byte-content-range-spec = bytes-unit SP
     *                                byte-range-resp-spec "/"
     *                                ( instance-length | "*" )
     *      byte-range-resp-spec    = (first-byte-pos "-" last-byte-pos)
     *                                | "*"
     *      instance-length         = 1*DIGIT.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.16
     */
    public const CONTENT_RANGE = 'Content-Range';

    /**
     * Content-Type Header.
     * The Content-Type entity-header field indicates the media type of the
     * entity-body sent to the recipient or, in the case of the HEAD method,
     * the media type that would have been sent had the request been a GET.
     *      Content-Type   = "Content-Type" ":" media-type.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.17
     */
    public const CONTENT_TYPE = 'Content-Type';

    /**
     * Date Header.
     * The Date general-header field represents the date and time at which
     * the message was originated, having the same semantics as orig-date
     * in RFC 822. The field value is an HTTP-date, as described in
     * section 3.3.1; it MUST be sent in RFC 1123 [8]-date format.
     *      Date = "Date" ":" HTTP-date.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.18
     */
    public const DATE = 'Date';

    /**
     * ETag Header.
     * The ETag response-header field provides the current value of the entity
     * tag for the requested variant. The headers used with entity tags are
     * described in sections 14.24, 14.26 and 14.44. The entity tag MAY be used
     * for comparison with other entities from the same resource
     * (see section 13.3.3).
     *      ETag = "ETag" ":" entity-tag.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.19
     */
    public const E_TAG = 'ETag';

    /**
     * Expect Header.
     * The Expect request-header field is used to indicate that particular
     * server behaviors are required by the client.
     *      Expect                = "Expect" ":" 1#expectation
     *      expectation           = "100-continue" | expectation-extension
     *      expectation-extension = token [ "=" ( token | quoted-string )
     *                              expect-params ]
     *      expect-params         = ";" token [ "=" ( token | quoted-string ) ].
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.20
     */
    public const EXPECT = 'Expect';

    /**
     * Expires Header.
     * The Expires entity-header field gives the date/time after which the
     * response is considered stale. A stale cache entry may not normally
     * be returned by a cache (either a proxy cache or a user agent cache)
     * unless it is first validated with the origin server (or with an
     * intermediate cache that has a fresh copy of the entity). See section
     * 13.2 for further discussion of the expiration model.
     * The presence of an Expires field does not imply that the original
     * resource will change or cease to exist at, before, or after that time.
     * The format is an absolute date and time as defined by HTTP-date
     * in section 3.3.1; it MUST be in RFC 1123 date format:
     *      Expires = "Expires" ":" HTTP-date.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.21
     */
    public const EXPIRES = 'Expires';

    /**
     * From Header.
     * The From request-header field, if given, SHOULD contain an Internet
     * e-mail address for the human user who controls the requesting user
     * agent. The address SHOULD be machine-usable, as defined by "mailbox"
     * in RFC 822 [9] as updated by RFC 1123 [8]:
     *      From = "From" ":" mailbox.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.22
     */
    public const FROM = 'From';

    /**
     * Host Header.
     * The Host request-header field specifies the Internet host and port
     * number of the resource being requested, as obtained from the original
     * URI given by the user or referring resource (generally an HTTP URL,
     * as described in section 3.2.2). The Host field value MUST represent
     * the naming authority of the origin server or gateway given by the
     * original URL. This allows the origin server or gateway to
     * differentiate between internally-ambiguous URLs, such as the root
     * "/" URL of a server for multiple host names on a single IP address.
     *      Host = "Host" ":" host [ ":" port ] ; Section 3.2.2.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.23
     */
    public const HOST = 'Host';

    /**
     * If-Match Header.
     * The If-Match request-header field is used with a method to make it
     * conditional. A client that has one or more entities previously
     * obtained from the resource can verify that one of those entities
     * is current by including a list of their associated entity tags in
     * the If-Match header field. Entity tags are defined in section 3.11.
     * The purpose of this feature is to allow efficient updates of cached
     * information with a minimum amount of transaction overhead. It is
     * also used, on updating requests, to prevent inadvertent modification
     * of the wrong version of a resource. As a special case, the value
     * "*" matches any current entity of the resource.
     *      If-Match = "If-Match" ":" ( "*" | 1#entity-tag ).
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.24
     */
    public const IF_MATCH = 'If-Match';

    /**
     * If-Modified-Since Header.
     * The If-Modified-Since request-header field is used with a method to
     * make it conditional: if the requested variant has not been modified
     * since the time  specified in this field, an entity will not be
     * returned from the server; instead, a 304 (not modified) response
     * will be returned without any message-body.
     *      If-Modified-Since = "If-Modified-Since" ":" HTTP-date.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.25
     */
    public const IF_MODIFIED_SINCE = 'If-Modified-Since';

    /**
     * If-None-Match Header.
     * The If-None-Match request-header field is used with a method to make
     * it conditional. A client that has one or more entities previously
     * obtained from the resource can verify that none of those entities
     * is current by including a list of their associated entity tags in
     * the If-None-Match header field. The purpose of this feature is to
     * allow efficient updates of cached information with a minimum amount
     * of transaction overhead. It is also used to prevent a method
     * (e.g. PUT) from inadvertently modifying an existing resource when
     * the client believes that the resource does not exist.
     * As a special case, the value "*" matches any current entity of
     * the resource.
     *      If-None-Match = "If-None-Match" ":" ( "*" | 1#entity-tag ).
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.26
     */
    public const IF_NONE_MATCH = 'If-None-Match';

    /**
     * If-Range Header.
     * If a client has a partial copy of an entity in its cache, and wishes
     * to have an up-to-date copy of the entire entity in its cache, it
     * could use the Range request-header with a conditional GET (using
     * either or both of If-Unmodified-Since and If-Match.) However, if
     * the condition fails because the entity has been modified, the client
     * would then have to make a second request to obtain the entire current
     * entity-body.
     * The If-Range header allows a client to "short-circuit" the second
     * request. Informally, its meaning is `if the entity is unchanged,
     * send me the part(s) that I am missing; otherwise, send me the
     * entire new entity'.
     *      If-Range = "If-Range" ":" ( entity-tag | HTTP-date ).
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.27
     */
    public const IF_RANGE = 'If-Range';

    /**
     * If-Unmodified-Since Header.
     * The If-Unmodified-Since request-header field is used with a method to
     * make it conditional. If the requested resource has not been modified
     * since the time specified in this field, the server SHOULD perform
     * the requested operation as if the If-Unmodified-Since header were
     * not present.
     * If the requested variant has been modified since the specified time,
     * the server MUST NOT perform the requested operation, and MUST return
     * a 412 (Precondition Failed).
     *      If-Unmodified-Since = "If-Unmodified-Since" ":" HTTP-date.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.28
     */
    public const IF_UNMODIFIED_SINCE = 'If-Unmodified-Since';

    /**
     * Last-Modified Header.
     * The Last-Modified entity-header field indicates the date and time
     * at which the origin server believes the variant was last modified.
     *      Last-Modified = "Last-Modified" ":" HTTP-date.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.29
     */
    public const LAST_MODIFIED = 'Last-Modified';

    /**
     * Location Header.
     * The Location response-header field is used to redirect the recipient
     * to a location other than the Request-URI for completion of the
     * request or identification of a new resource. For 201 (Created)
     * responses, the Location is that of the new resource which was
     * created by the request. For 3xx responses, the location SHOULD
     * indicate the server's preferred URI for automatic redirection
     * to the resource. The field value consists of a single absolute URI.
     *      Location = "Location" ":" absoluteURI.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.30
     */
    public const LOCATION = 'Location';

    /**
     * Max-Forwards Header.
     * The Max-Forwards request-header field provides a mechanism with the
     * TRACE (section 9.8) and OPTIONS (section 9.2) methods to limit the
     * number of proxies or gateways that can forward the request to the
     * next inbound server. This can be useful when the client is attempting
     * to trace a request chain which appears to be failing or looping
     * in mid-chain.
     *      Max-Forwards = "Max-Forwards" ":" 1*DIGIT.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.31
     */
    public const MAX_FORWARDS = 'Max-Forwards';

    /**
     * Pragma Header.
     * The Pragma general-header field is used to include implementation-
     * specific directives that might apply to any recipient along the
     * request/response chain. All pragma directives specify optional
     * behavior from the viewpoint of the protocol; however, some systems
     * MAY require that behavior be consistent with the directives.
     *      Pragma           = "Pragma" ":" 1#pragma-directive
     *      pragma-directive = "no-cache" | extension-pragma
     *      extension-pragma = token [ "=" ( token | quoted-string ) ].
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.32
     */
    public const PRAGMA = 'Pragma';

    /**
     * Proxy-Authenticate Header.
     * The Proxy-Authenticate response-header field MUST be included as
     * part of a 407 (Proxy Authentication Required) response. The field
     * value consists of a challenge that indicates the authentication
     * scheme and parameters applicable to the proxy for this Request-URI.
     *      Proxy-Authenticate = "Proxy-Authenticate" ":" 1#challenge.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.33
     */
    public const PROXY_AUTHENTICATE = 'Proxy-Authenticate';

    /**
     * Proxy-Authorization Header.
     * The Proxy-Authorization request-header field allows the client to
     * identify itself (or its user) to a proxy which requires
     * authentication. The Proxy-Authorization field value consists of
     * credentials containing the authentication information of he user
     * agent for the proxy and/or realm of the resource being requested.
     *      Proxy-Authorization = "Proxy-Authorization" ":" credentials.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.34
     */
    public const PROXY_AUTHORIZATION = 'Proxy-Authorization';

    /**
     * Range Header.
     * Since all HTTP entities are represented in HTTP messages as sequences
     * of bytes, the concept of a byte range is meaningful for any HTTP
     * entity. (However, not all clients and servers need to support
     * byte-range operations.)
     * Byte range specifications in HTTP apply to the sequence of bytes in
     * the entity-body (not necessarily the same as the message-body).
     * A byte range operation MAY specify a single range of bytes, or a set
     * of ranges within a single entity.
     *      ranges-specifier      = byte-ranges-specifier
     *      byte-ranges-specifier = bytes-unit "=" byte-range-set
     *      byte-range-set        = 1#( byte-range-spec |
     *      suffix-byte-range-spec ) byte-range-spec       = first-byte-pos "-"
     *      [last-byte-pos] first-byte-pos        = 1*DIGIT last-byte-pos
     *        = 1*DIGIT
     * The first-byte-pos value in a byte-range-spec gives the byte-offset
     * of the first byte in a range. The last-byte-pos value gives the
     * byte-offset of the last byte in the range; that is, the byte
     * positions specified are inclusive. Byte offsets start at zero.
     * If the last-byte-pos value is present, it MUST be greater than or
     * equal to the first-byte-pos in that byte-range-spec, or
     * the byte-range-spec is syntactically invalid. The recipient of a
     * byte-range-set that includes one or more syntactically invalid
     * byte-range-spec values MUST ignore the header field that includes
     * that byte-range-set.
     * If the last-byte-pos value is absent, or if the value is greater
     * than or equal to the  current length of the entity-body,
     * last-byte-pos is taken to be equal to one less than the current
     * length of the entity-body in bytes.
     * By its choice of last-byte-pos, a client can limit the number
     * of bytes retrieved without knowing the size of the entity.
     *      suffix-byte-range-spec = "-" suffix-length
     *      suffix-length          = 1*DIGIT
     * HTTP retrieval requests using conditional or unconditional GET
     * methods MAY request one or more sub-ranges of the entity,
     * instead of the entire entity, using the Range request header,
     * which applies to the entity returned as the result of the request:
     *      Range = "Range" ":" ranges-specifier.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.35
     */
    public const RANGE = 'Range';

    /**
     * Referer Header.
     * The Referer[sic] request-header field allows the client to specify,
     * for the server's benefit, the address (URI) of the resource from
     * which the Request-URI was obtained (the "referrer", although the
     * header field is misspelled.) The Referer request-header allows a
     * server to generate lists of back-links to resources for interest,
     * logging, optimized caching, etc. It also allows obsolete or mistyped
     * links to be traced for maintenance. The Referer field MUST NOT be
     * sent if the Request-URI was obtained from a source that does not
     * have its own URI, such as input from the user keyboard.
     *      Referer = "Referer" ":" ( absoluteURI | relativeURI ).
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.36
     */
    public const REFERER = 'Referer';

    /**
     * Retry-After Header.
     * The Retry-After response-header field can be used with a 503
     * (Service Unavailable) response to indicate how long the service is
     * expected to be unavailable to the requesting client. This field MAY
     * also be used with any 3xx (Redirection) response to indicate the
     * minimum time the user-agent is asked wait before issuing the
     * redirected request. The value of this field can be either an HTTP-date
     * or an integer number of seconds (in decimal) after the time of the
     * response.
     *      Retry-After = "Retry-After" ":" ( HTTP-date | delta-seconds ).
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.37
     */
    public const RETRY_AFTER = 'Retry-After';

    /**
     * Server Header.
     * The Server response-header field contains information about the
     * software used by the origin server to handle the request. The field
     * can contain multiple product tokens (section 3.8) and comments
     * identifying the server and any significant subproducts. The product
     * tokens are listed in order of their significance for identifying
     * the application.
     *      Server = "Server" ":" 1*( product | comment ).
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.38
     */
    public const SERVER = 'Server';

    /**
     * Set-Cookie Header.
     * The Set-Cookie HTTP response header is used to send cookies from the
     * server to the user agent.
     * Informally, the Set-Cookie response header contains the header name
     * "Set-Cookie" followed by a ":" and a cookie.  Each cookie begins with
     * a name-value-pair, followed by zero or more attribute-value pairs.
     * Servers SHOULD NOT send Set-Cookie headers that fail to conform to
     * the following grammar:
     *      set-cookie-header = "Set-Cookie:" SP set-cookie-string
     *      set-cookie-string = cookie-pair *( ";" SP cookie-av )
     *      cookie-pair       = cookie-name "=" cookie-value
     *      cookie-name       = token
     *      cookie-value      = *cookie-octet / ( DQUOTE *cookie-octet DQUOTE )
     *      cookie-octet      = %x21 / %x23-2B / %x2D-3A / %x3C-5B / %x5D-7E
     *                            ; US-ASCII characters excluding CTLs,
     *                            ; whitespace DQUOTE, comma, semicolon,
     *                            ; and backslash
     *      token             = <token, defined in [RFC2616], Section 2.2>
     *      cookie-av         = expires-av / max-age-av / domain-av /
     *      path-av / secure-av / httponly-av /
     *      extension-av
     *      expires-av        = "Expires=" sane-cookie-date
     *      sane-cookie-date  = <rfc1123-date, defined in [RFC2616], Section 3.3.1>
     *      max-age-av        = "Max-Age=" non-zero-digit *DIGIT
     *                            ; In practice, both expires-av and max-age-av
     *                            ; are limited to dates representable by the
     *                            ; user agent.
     *      non-zero-digit    = %x31-39
     *                            ; digits 1 through 9
     *      domain-av         = "Domain=" domain-value
     *      domain-value      = <subdomain>
     *                            ; defined in [RFC1034], Section 3.5, as
     *                            ; enhanced by [RFC1123], Section 2.1
     *      path-av           = "Path=" path-value
     *      path-value        = <any CHAR except CTLs or ";">
     *      secure-av         = "Secure"
     *      httponly-av       = "HttpOnly"
     *      extension-av      = <any CHAR except CTLs or ";">.
     *
     * @link https://tools.ietf.org/html/rfc6265#section-4.1
     */
    public const SET_COOKIE = 'Set-Cookie';

    /**
     * TE Header.
     * The TE request-header field indicates what extension transfer-codings
     * it is willing to accept in the response and whether or not it is
     * willing to accept trailer fields in a chunked transfer-coding. Its
     * value may consist of the keyword "trailers" and/or a comma-separated
     * list of extension transfer-coding names with optional accept
     * parameters (as described in section 3.6).
     *      TE        = "TE" ":" #( t-codings )
     *      t-codings = "trailers" | ( transfer-extension [ accept-params ] ).
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.39
     */
    public const TE = 'TE';

    /**
     * Trailer Header.
     * The Trailer general field value indicates that the given set of
     * header fields is present in the trailer of a message encoded with
     * chunked transfer-coding.
     *      Trailer = "Trailer" ":" 1#field-name.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.40
     */
    public const TRAILER = 'Trailer';

    /**
     * Transfer-Encoding Header.
     * The Transfer-Encoding general-header field indicates what (if any)
     * type of transformation has been applied to the message body in order
     * to safely transfer it between the sender and the recipient. This
     * differs from the content-coding in that the transfer-coding is a
     * property of the message, not of the entity.
     *      Transfer-Encoding = "Transfer-Encoding" ":" 1#transfer-coding.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.41
     */
    public const TRANSFER_ENCODING = 'Transfer-Encoding';

    /**
     * Upgrade Header.
     * The Upgrade general-header allows the client to specify what
     * additional communication protocols it supports and would like
     * to use if the server finds it appropriate to switch protocols.
     * The server MUST use the Upgrade header field within a 101
     * (Switching Protocols) response to indicate which protocol(s)
     * are being switched.
     *      Upgrade = "Upgrade" ":" 1#product.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.42
     */
    public const UPGRADE = 'Upgrade';

    /**
     * User-Agent Header.
     * The User-Agent request-header field contains information about the
     * user agent originating the request. This is for statistical purposes,
     * the tracing of protocol violations, and automated recognition of user
     * agents for the sake of tailoring responses to avoid particular user
     * agent limitations. User agents SHOULD include this field with
     * requests. The field can contain multiple product tokens (section 3.8)
     * and comments identifying the agent and any subproducts which form
     * a significant part of the user agent. By convention, the product
     * tokens are listed in order of their significance for identifying
     * the application.
     *      User-Agent = "User-Agent" ":" 1*( product | comment ).
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.43
     */
    public const USER_AGENT = 'User-Agent';

    /**
     * Vary Header.
     * The Vary field value indicates the set of request-header fields that
     * fully determines, while the response is fresh, whether a cache is
     * permitted to use the response to reply to a subsequent request
     * without revalidation. For uncacheable or stale responses, the Vary
     * field value advises the user agent about the criteria that were
     * used to select the representation. A Vary field value of "*" implies
     * that a cache cannot determine from the request headers of a
     * subsequent request whether this response is the appropriate
     * representation. See section 13.6 for use of the Vary header
     * field by caches.
     *      Vary = "Vary" ":" ( "*" | 1#field-name ).
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.44
     */
    public const VARY = 'Vary';

    /**
     * Via Header.
     * The Via general-header field MUST be used by gateways and proxies to
     * indicate the intermediate protocols and recipients between the user
     * agent and the server on requests, and between the origin server and
     * the client on responses. It is analogous to the "Received" field of
     * RFC 822 [9] and is intended to be used for tracking message forwards,
     * avoiding request loops, and identifying the protocol capabilities of
     * all senders along the request/response chain.
     *      Via               =  "Via" ":" 1#( received-protocol received-by [
     *      comment ] ) received-protocol = [ protocol-name "/" ]
     *      protocol-version protocol-name     = token protocol-version  =
     *      token received-by       = ( host [ ":" port ] ) | pseudonym
     *      pseudonym         = token.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.45
     */
    public const VIA = 'Via';

    /**
     * Warning Header.
     * The Warning general-header field is used to carry additional
     * information about the status or transformation of a message
     * which might not be reflected in the message. This information
     * is typically used to warn about a possible lack of semantic
     * transparency from caching operations or transformations applied
     * to the entity body of the message.
     * Warning headers are sent with responses using:
     *      Warning       = "Warning" ":" 1#warning-value
     *      warning-value = warn-code SP warn-agent SP warn-text
     *                      [SP warn-date]
     *      warn-code     = 3DIGIT
     *      warn-agent    = ( host [ ":" port ] ) | pseudonym
     *                      ; the name or pseudonym of the server adding
     *                      ; the Warning header, for use in debugging
     *      warn-text     = quoted-string
     *      warn-date     = <"> HTTP-date <">.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.46
     */
    public const WARNING = 'Warning';

    /**
     * WWW-Authenticate Header.
     * The WWW-Authenticate response-header field MUST be included in 401
     * (Unauthorized) response messages. The field value consists of at
     * least one challenge that indicates the authentication scheme(s)
     * and parameters applicable to the Request-URI.
     *      WWW-Authenticate = "WWW-Authenticate" ":" 1#challenge.
     *
     * @link https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.47
     */
    public const WWW_AUTHENTICATE = 'WWW-Authenticate';
}
