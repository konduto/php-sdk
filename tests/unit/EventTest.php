<?php namespace Konduto\Tests;

use Konduto\Models\Event as Event;
use Konduto\Models\Ticket as Ticket;
use Konduto\Models\Venue as Venue;
use Konduto\Models\Attendee as Attendee;

class EventTest extends \PHPUnit_Framework_TestCase {

    function test_build_and_getters() {
        $eventArr = array(
            "name" => "Cirque de La Lune",
            "date" => "2020-02-10T20:00:00Z",
            "type" => "show",
            "subtype" => "circus",
            "venue" => array(
               "name" => "United Hall",
               "address" => "Av. das Nações",
               "city" => "São Paulo",
               "state" => "SP",
               "country" => "Brazil",
               "capacity" => 7000
            ),
            "tickets" => array(
                array(
                    "id" => "t1012390",
                    "category" => "student",
                    "section" => "pista premium",
                    "premium" => true,
                    "attendee" => array(
                        "name" => "Alfredo Borges",
                        "document" => "12345678900",
                        "document_type" => "cpf",
                        "dob" => "2000-02-20"
                    )
                )
            )
        );

        $event = new Event($eventArr);
        $this->assertEquals("Cirque de La Lune", $event->getName());
        $this->assertInstanceOf('\DateTime', $event->getDate());
        $this->assertEquals("show", $event->getType());
        $this->assertEquals("circus", $event->getSubtype());
        $this->assertEquals(1, count($event->getTickets()));


        $venue = $event->getVenue();
        $this->assertEquals("United Hall", $venue->getName());
        $this->assertEquals("Av. das Nações", $venue->getAddress());
        $this->assertEquals("São Paulo", $venue->getCity());
        $this->assertEquals("SP", $venue->getState());
        $this->assertEquals("Brazil", $venue->getCountry());
        $this->assertEquals(7000, $venue->getCapacity());

        $ticket = $event->getTickets()[0];
        $this->assertEquals("t1012390", $ticket->getId());
        $this->assertEquals("student", $ticket->getCategory());
        $this->assertEquals("pista premium", $ticket->getSection());
        $this->assertEquals(true, $ticket->getPremium());
        
        $attendee = $ticket->getAttendee();
        $this->assertEquals("Alfredo Borges", $attendee->getName());
        $this->assertEquals("12345678900", $attendee->getDocument());
        $this->assertEquals("cpf", $attendee->getDocumentType());
        $this->assertInstanceOf('\DateTime', $attendee->getDob());
    }

    function test_setters_and_getters() {
        $attendee = new Attendee();
        $attendee->setName("Alfredo Borges");
        $attendee->setDocument("12345678900");
        $attendee->setDocumentType("cpf");
        $attendee->setDob("2000-02-20");

        $ticket = new Ticket();
        $ticket->setId("t1012390");
        $ticket->setCategory("student");
        $ticket->setSection("pista premium");
        $ticket->setPremium(true);
        $ticket->setAttendee($attendee);

        $venue = new Venue();
        $venue->setName("United Hall");
        $venue->setAddress("Av. das Nações");
        $venue->setCity("São Paulo");
        $venue->setState("SP");
        $venue->setCountry("Brazil");
        $venue->setCapacity(7000);

        $event = new Event();
        $event->setName("Cirque de La Lune");
        $event->setDate("2020-02-10T20:00:59Z");
        $event->setType("show");
        $event->setSubtype("circus");
        $event->setVenue($venue);
        $event->setTickets($ticket);

        $this->assertEquals("Cirque de La Lune", $event->getName());
        $this->assertInstanceOf('\DateTime', $event->getDate());
        $this->assertEquals("show", $event->getType());
        $this->assertEquals("circus", $event->getSubtype());
        $this->assertEquals(1, count($event->getTickets()));

        $venue = $event->getVenue();
        $this->assertEquals("United Hall", $venue->getName());
        $this->assertEquals("Av. das Nações", $venue->getAddress());
        $this->assertEquals("São Paulo", $venue->getCity());
        $this->assertEquals("SP", $venue->getState());
        $this->assertEquals("Brazil", $venue->getCountry());
        $this->assertEquals(7000, $venue->getCapacity());

        $ticket = $event->getTickets()[0];
        $this->assertEquals("t1012390", $ticket->getId());
        $this->assertEquals("student", $ticket->getCategory());
        $this->assertEquals("pista premium", $ticket->getSection());
        $this->assertEquals(true, $ticket->getPremium());
        
        $attendee = $ticket->getAttendee();
        $this->assertEquals("Alfredo Borges", $attendee->getName());
        $this->assertEquals("12345678900", $attendee->getDocument());
        $this->assertEquals("cpf", $attendee->getDocumentType());
        $this->assertInstanceOf('\DateTime', $attendee->getDob());
    }
}
