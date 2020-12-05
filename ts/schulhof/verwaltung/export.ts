import personen from "./personen";
import rechte from "./rechte";
import moduleI from "./module";

export default {
  personen: personen,
  ...rechte,
  module: moduleI,
};