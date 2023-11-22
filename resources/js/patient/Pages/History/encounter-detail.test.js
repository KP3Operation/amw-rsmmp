/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import EncounterDetailPage from "./EncounterDetail.vue";

describe("EncounterDetailPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(EncounterDetailPage);
        expect(wrapper.exists()).toBe(true);
    });
});
