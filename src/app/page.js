import ImageBanner from "@/components/ImageBanner/ImageBanner";
import Copy from "@/components/Copy/Copy";
import Header from "@/components/Header/Header";

import "./landing.css";

const TRIPS = [
  { title: "Cycling the Coast", text: "Pedal along the sea", img: "/canoa.jpg" },
  { title: "Mountain Trails", text: "Ride the highlands", img: "/hero.jpg" },
  { title: "Island Getaway", text: "Slow days on the water", img: "/landing.jpg" },
  { title: "Wine Country", text: "Vineyards by bike", img: "/photo.jpg" },
];

export default function Home() {
  return (
    <>
      <Header />

      <section className="landing">
        <Copy type="lines" animateOnScroll={false} delay={0.15}>
          <h1 className="landing-title">
            Escargot <span className="landing-title-italic">Adventures</span>
          </h1>
        </Copy>

        <div className="landing-photo">
          <img src="/landing.jpg" alt="" />
        </div>
      </section>

      <section className="about-agency" id="about">
        <div className="about-agency-text">
          <Copy type="lines" animateOnScroll>
            <p>
              Escargot is a travel agency eco-friendly, focusing on cycling and
              exotic tourism.
            </p>
          </Copy>
        </div>
        <div className="about-agency-cta">
          <a href="#">Explore trips</a>
        </div>
      </section>

      <section className="destination" id="destination">
        <div className="destination-copy">
          <h2 className="destination-title">Destination</h2>
          <p className="destination-text">
            Travel slowly and discover places at your own pace.
          </p>
          <img className="destination-logo" src="/logo-verde.png" alt="" />
        </div>
        <div className="destination-photo">
          <img src="/photo.jpg" alt="" />
        </div>
      </section>

      <ImageBanner image="/canoa.jpg" logo="/logo-verde.png" />

      <section className="trips">
        <h2 className="trips-heading">Explore our trips</h2>
        <div className="trips-grid">
          {TRIPS.map((trip) => (
            <div className="trip-card" key={trip.title}>
              <div className="trip-card-img">
                <img src={trip.img} alt="" />
              </div>
              <div className="trip-card-body">
                <h3 className="trip-card-title">{trip.title}</h3>
                <p className="trip-card-text">{trip.text}</p>
                <a className="trip-card-btn" href="#">
                  View trip
                </a>
              </div>
            </div>
          ))}
        </div>
      </section>

      <section className="contact" id="contact">
        <h2 className="contact-heading">Contact</h2>
        <form className="contact-form">
          <input type="text" name="name" placeholder="Name" />
          <input type="email" name="email" placeholder="Email" />
          <textarea name="message" placeholder="Message" rows="5" />
          <button type="submit">Send</button>
        </form>
      </section>
    </>
  );
}
