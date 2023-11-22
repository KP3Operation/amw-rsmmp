/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import EncounterCardComponent from "./EncounterCard.vue";

describe("EncounterCardComponent", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(EncounterCardComponent);
        expect(wrapper.exists()).toBe(true);
    });
});
