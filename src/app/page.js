import ImageBanner from "@/components/ImageBanner/ImageBanner";

import "./landing.css";

export default function Home() {
  return (
    <>
      <section className="landing">
        <h1 className="landing-title">
          Escargot <span className="landing-title-italic">Adventures</span>
        </h1>

        <div className="landing-photo">
          <img src="/landing.jpg" alt="" />
        </div>
      </section>

      <section className="about-agency">
        <div className="about-agency-text">
          <p>
            Escargot is a travel agency eco-friendly, focusing on cycling and
            exotic tourism.
          </p>
        </div>
        <div className="about-agency-cta">
          <a href="#">Explore trips</a>
        </div>
      </section>

      <ImageBanner image="/canoa.jpg" logo="/logo.png" />
    </>
  );
}
