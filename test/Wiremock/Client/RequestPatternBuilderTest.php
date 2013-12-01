<?php

namespace WireMock\Client;

use WireMock\Matching\UrlMatchingStrategy;

class RequestPatternBuilderTest extends \PHPUnit_Framework_TestCase
{
    function testMethodAndUrlMatchingStrategyAreInArray()
    {
        // given
        $method = 'GET';
        $matchingType = 'url';
        $matchingValue = '/some/url';
        /** @var UrlMatchingStrategy $mockUrlMatchingStrategy */
        $mockUrlMatchingStrategy = mock('WireMock\Matching\UrlMatchingStrategy');
        when($mockUrlMatchingStrategy->toArray())->return(array($matchingType => $matchingValue));
        $requestPatternBuilder = new RequestPatternBuilder($method, $mockUrlMatchingStrategy);

        // when
        $requestPatternArray = $requestPatternBuilder->build()->toArray();

        // then
        assertThat($requestPatternArray, hasEntry('method', $method));
        assertThat($requestPatternArray, hasEntry($matchingType, $matchingValue));
    }

    function testHeaderWithValueMatchingStrategyIsInArrayIfSpecified()
    {
        // given
        /** @var UrlMatchingStrategy $mockUrlMatchingStrategy */
        $mockUrlMatchingStrategy = mock('WireMock\Matching\UrlMatchingStrategy');
        when($mockUrlMatchingStrategy->toArray())->return(array('url' => '/some/url'));
        $requestPatternBuilder = new RequestPatternBuilder('GET', $mockUrlMatchingStrategy);
        /** @var ValueMatchingStrategy $mockValueMatchingStrategy */
        $mockValueMatchingStrategy = mock('WireMock\Client\ValueMatchingStrategy');
        when($mockValueMatchingStrategy->toArray())->return(array('equalTo' => 'something'));
        $requestPatternBuilder->withHeader('Some-Header', $mockValueMatchingStrategy);

        // when
        $requestPatternArray = $requestPatternBuilder->build()->toArray();

        // then
        assertThat($requestPatternArray, hasEntry('headers', array('Some-Header' => array('equalTo' => 'something'))));
    }
}